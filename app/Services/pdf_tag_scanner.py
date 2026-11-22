import sys
import os
import json

def scan_and_convert(pdf_path, png_path):
    try:
        import fitz
    except ImportError:
        try:
            import pymupdf as fitz
        except ImportError:
            print(json.dumps({"status": "no_fitz"}))
            sys.exit(0)

    try:
        if not os.path.exists(pdf_path):
            print(json.dumps({"status": "error", "message": "PDF file not found"}))
            sys.exit(0)

        # Pastikan folder tujuan PNG ada
        png_dir = os.path.dirname(os.path.abspath(png_path))
        if png_dir and not os.path.exists(png_dir):
            os.makedirs(png_dir, exist_ok=True)

        doc = fitz.open(pdf_path)
        if len(doc) == 0:
            print(json.dumps({"status": "empty_pdf"}))
            sys.exit(0)

        page = doc[0]

        # Daftar variasi rumus/tag yang mungkin diketik pengguna di Canva
        tags_map = {
            'nomor': [
                '[$nomor$]', '[$NOMOR$]', '[$Nomor$]',
                '[nomor]', '[NOMOR]', '[Nomor]',
                '[$no$]', '[$NO$]', '[no]', '[NO]',
                '{nomor}', '{NOMOR}', '[$ nomor $]'
            ],
            'nama': [
                '[$nama$]', '[$NAMA$]', '[$Nama$]',
                '[nama]', '[NAMA]', '[Nama]',
                '[$nama_peserta$]', '[$NAMA_PESERTA$]',
                '{nama}', '{NAMA}', '[$ nama $]'
            ],
            'predikat': [
                '[$predikat$]', '[$PREDIKAT$]', '[$Predikat$]',
                '[predikat]', '[PREDIKAT]',
                '[$juara$]', '[$JUARA$]', '[$Juara$]',
                '[juara]', '[JUARA]',
                '{predikat}', '{juara}', '[$ predikat $]'
            ]
        }

        detected = {}

        for key, variants in tags_map.items():
            for v in variants:
                rects = page.search_for(v)
                if rects:
                    r = rects[0]
                    # Konversi poin (72 pt/inci) ke milimeter (25.4 mm/inci)
                    left_mm = round(r.x0 * 25.4 / 72, 1)
                    top_mm = round(r.y0 * 25.4 / 72, 1)
                    width_mm = round((r.x1 - r.x0) * 25.4 / 72, 1)
                    height_mm = round((r.y1 - r.y0) * 25.4 / 72, 1)
                    font_size_pt = round(r.y1 - r.y0, 1)

                    detected[key] = {
                        'left': left_mm,
                        'top': top_mm,
                        'width': width_mm,
                        'height': height_mm,
                        'font_size': font_size_pt
                    }

                    # Redact / tutupi teks rumus dengan warna putih bersih di gambar background
                    page.add_redact_annot(r, fill=(1, 1, 1))
                    break

        page.apply_redactions()
        pix = page.get_pixmap(dpi=200)
        pix.save(png_path)

        result = {
            "status": "success",
            "detected_tags": detected
        }
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))

if __name__ == '__main__':
    if len(sys.argv) >= 3:
        scan_and_convert(sys.argv[1], sys.argv[2])
    else:
        print(json.dumps({"status": "invalid_args"}))
