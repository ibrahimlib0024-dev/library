# Astra Library — Project Notes

محتوى مختصر حول المشروع وإجراءات النسخ الاحتياطي والاستيجينج.

## النسخ الاحتياطي المحلي (Windows / XAMPP)

هناك سكربت PowerShell بسيط في `scripts/backup.ps1` يقوم بتفريغ قاعدة البيانات وضغط مجلدات مهمة.

استخدام (PowerShell):

```powershell
cd C:\xampp\htdocs\library\scripts
.\backup.ps1
```

عدل المتغيّرات داخل السكربت (`$dbName`, `$dbUser`, `$dbPass`) إذا احتجت.

## استراتيجيات الاستيجينج
- استخدم نسخة محلية منفصلة من قاعدة البيانات وبيئة XAMPP منفصلة أو خادم VPS للاختبار.
- احتفظ بالنسخ الاحتياطية من الملفات وقاعدة البيانات قبل أي ترقية أو تغييرات كبيرة.
- إن أمكن، استخدم خدمات استضافة تدعم staging (مثل WP Engine, Kinsta) أو إعداد خادم فرعي.

## ملاحظات أمان
## Remote & CI


```powershell
git remote add origin git@github.com:yourusername/yourrepo.git
git branch -M main
git push -u origin main
```


## Import sample data

To test the CSV importer (plugin `Library CSV Importer`), go to WordPress admin → Tools → Library Import, and upload the sample file `scripts/sample-books.csv`.

CSV columns supported: `title,content,short,year,isbn,pdf,authors,publishers,genres,cover_url`.

- لا تُخزن `wp-config.php` في مستودع علني — ملف `.gitignore` تم تهيئته لاستبعاده.
- اختبر التغييرات أولًا في بيئة staging.
