<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupSeasonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-season {--name= : Optional custom label for backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cadangkan seluruh basis data penilaian dan media Season II ke arsip ZIP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai prosedur pencadangan data Spencerone Camp Season II...');

        $timestamp = date('Y-m-d_H-i-s');
        $label = $this->option('name') ? '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $this->option('name')) : '';
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $sqlFilename = "database_dump_{$timestamp}{$label}.sql";
        $sqlPath = $backupDir . DIRECTORY_SEPARATOR . $sqlFilename;
        $zipFilename = "spencerone_camp_backup_{$timestamp}{$label}.zip";
        $zipPath = $backupDir . DIRECTORY_SEPARATOR . $zipFilename;

        // 1. Ekspor Database ke SQL
        $this->line('1. Mengekspor tabel database ke SQL...');
        $sqlContent = $this->dumpDatabase();
        File::put($sqlPath, $sqlContent);
        $this->info("   Basis data berhasil diekspor (" . number_format(filesize($sqlPath) / 1024, 2) . " KB)");

        // 2. Arsipkan ke ZIP termasuk storage public
        $this->line('2. Mengompresi basis data dan direktori media ke ZIP...');
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($sqlPath, $sqlFilename);

            $publicStorage = storage_path('app/public');
            if (File::isDirectory($publicStorage)) {
                $files = File::allFiles($publicStorage);
                foreach ($files as $file) {
                    $relativePath = 'storage/' . $file->getRelativePathname();
                    $zip->addFile($file->getRealPath(), $relativePath);
                }
            }

            $zip->close();
            $this->info("   Arsip ZIP berhasil dibuat: {$zipFilename} (" . number_format(filesize($zipPath) / 1024, 2) . " KB)");
        } else {
            $this->error('   Gagal membuat berkas arsip ZIP.');
            return 1;
        }

        // Hapus file sql sementara
        File::delete($sqlPath);

        $this->info("Pencadangan selesai! Berkas tersimpan di: {$zipPath}");
        return 0;
    }

    /**
     * Menghasilkan SQL dump dari semua tabel via PDO
     */
    private function dumpDatabase(): string
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tableKey = "Tables_in_" . $dbName;

        $output = "-- Spencerone Camp Season II Database Backup\n";
        $output .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Database: " . $dbName . "\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $tableObj) {
            $tableName = $tableObj->$tableKey ?? current((array)$tableObj);
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createSql = $createTable[0]->{'Create Table'} ?? '';

            $output .= "-- Struktur tabel `{$tableName}`\n";
            $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $output .= $createSql . ";\n\n";

            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $output .= "-- Data untuk tabel `{$tableName}`\n";
                foreach ($rows as $row) {
                    $values = array_map(function ($val) {
                        if (is_null($val)) return "NULL";
                        return "'" . addslashes((string)$val) . "'";
                    }, (array)$row);

                    $output .= "INSERT INTO `{$tableName}` VALUES (" . implode(", ", $values) . ");\n";
                }
                $output .= "\n";
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $output;
    }
}
