# متابعة الأخصائي عبر PIN — دليل الموبايل والسيرفر

Base URL: `{APP_URL}/api`  
Auth: `Authorization: Bearer {sanctum_token}`  
Content-Type: `application/json` أو `multipart/form-data` إذا في صورة.

---

## الفكرة

عند التسجيل أو تعديل البيانات المريض يختار:

| الخيار | ماذا يرسل |
|--------|-----------|
| متابعة بنفسي | لا يرسل PIN، أو يرسل `follow_with_specialist = false` |
| متابعة مع أخصائي | `follow_with_specialist = true` **و** `specialist_pin` (6 أرقام) |

PIN الأخصائي يظهر في لوحة الأخصائي (السايد بار). بعد الربط، تقدم الأصوات والمراحل يظهر عند ذلك الأخصائي فقط.

تطبيق قديم **لا يرسل** الحقول الجديدة يبقى يعمل: المريض يتابع بنفسه.

---

## الحقول الجديدة

| الحقل | النوع | متى مطلوب |
|--------|--------|-----------|
| `follow_with_specialist` | boolean (`true`/`false`/`1`/`0`) | اختياري. `true` = مع أخصائي |
| `specialist_pin` | string، 6 أرقام. المسافات تُحذف تلقائياً | **إلزامي** إذا `follow_with_specialist = true`. إذا أُرسل PIN بدون العلم، يُفسَّر كمتابعة مع أخصائي |

---

## 1) تسجيل جديد — إكمال الملف

`POST /api/complete-profile`  
Middleware: `auth:sanctum` فقط (قبل اكتمال الملف).

الحقول القديمة كما هي: `name`, `age`, `gender`, `profile_picture?`

### بنفسي

```json
{
  "name": "أحمد",
  "age": 3,
  "gender": "male",
  "follow_with_specialist": false
}
```

أو نفس الطلب القديم بدون أي حقل PIN.

### مع أخصائي

```json
{
  "name": "أحمد",
  "age": 3,
  "gender": "male",
  "follow_with_specialist": true,
  "specialist_pin": "305910"
}
```

الرد الناجح `200`:

```json
{
  "status": true,
  "message": "تمت العملية بنجاح",
  "data": {
    "id": 12,
    "name": "أحمد",
    "follow_with_specialist": true,
    "specialist_pin": "305910",
    "specialist_name": "اسم الأخصائي",
    "...": "باقي حقول الملف كما السابق"
  }
}
```

---

## 2) تعديل لاحق (إضافة PIN أو تغييره أو إلغاؤه)

`POST /api/update-profile`  
Middleware: `auth:sanctum` + `completed-profile`

كل الحقول اختيارية. يكفي إرسال PIN فقط.

### إضافة / تغيير الأخصائي

```json
{
  "follow_with_specialist": true,
  "specialist_pin": "305910"
}
```

أو:

```json
{
  "specialist_pin": "305910"
}
```

### الرجوع للمتابعة بالنفس

```json
{
  "follow_with_specialist": false
}
```

يمكن أيضاً تعديل `name` / `age` / `gender` / `profile_picture` في نفس الطلب.

---

## 3) عرض معلومات المريض

`GET /api/user-details`  
Middleware: `auth:sanctum` + `completed-profile`

حقول المتابعة المضافة على الـ payload السابق:

```json
{
  "follow_with_specialist": true,
  "specialist_pin": "305910",
  "specialist_name": "اسم الأخصائي"
}
```

إذا يتابع بنفسه:

```json
{
  "follow_with_specialist": false,
  "specialist_pin": null,
  "specialist_name": null
}
```

اعرض PIN في شاشة الملف الشخصي إذا `follow_with_specialist === true`.

---

## أخطاء التحقق `422`

| الحالة | الحقل | المعنى |
|--------|--------|--------|
| اختار أخصائي بدون PIN | `specialist_pin` | PIN مطلوب |
| PIN ليس 6 أرقام أو لا يخص أخصائي فعّال | `specialist_pin` | رمز غير صحيح |
| باقة الأخصائي وصلت حد المرضى | `specialist_pin` | الأخصائي ممتلئ |

لا تكمل الربط إذا رجع 422. خيار «بنفسي» لا يحتاج PIN.

---

## فلتر لوحة الأخصائي

ما في باراميتر فلتر من الموبايل. الربط يتم بتخزين `followed_specialist_id` عند المريض.

- الأخصائي يملك `specialist_code` فريد (PIN).
- المريض **لا** يخزّن نفس العمود (كان unique ويمنع أكثر من مريض).
- تقدم الأصوات (`/specialist/sounds-progress`) والمراحل (`/specialist/stages-progress`) يعرضان مرضى هذا الأخصائي فقط.

---

## رفع السيرفر: حماية `.env` قبل `git pull`

`.env` كان متتتبَع بالغلط. بعد هذا الرفع Git **سيحذف** `.env` من مجلد المشروع على السيرفر عند السحب. نفّذ بالترتيب و**لا تتخطّى** الخطوة 1.

### 1) على السيرفر — قبل أي `git pull`

من جذر المشروع (نفس المجلد الذي فيه `.env` و`.git`):

```bash
pwd
ls -la .env
cp -a .env "/root/nanteq.env.bak.$(date +%Y%m%d%H%M%S)"
cp -a .env .env.keep
```

تأكد أن النسختين موجودتان:

```bash
ls -la .env .env.keep /root/nanteq.env.bak.*
```

لا تغلق الجلسة ولا تحذف هذه النسخ.

### 2) سحب الكود

```bash
git status
git stash push -u -m "wip-before-specialist-pull"
git pull origin main
```

إذا `git pull` رفض بسبب ملفات معدّلة، لا تعمل `reset --hard`. حلّ التعارض أو اترك الـ stash ثم أعد المحاولة.

### 3) إعادة `.env` فوراً إذا اختفى

```bash
if [ ! -f .env ]; then
  cp -a .env.keep .env
fi
ls -la .env
```

إذا `.env.keep` غير موجود:

```bash
cp -a /root/nanteq.env.bak.* .env
```

(استخدم أحدث ملف باك أب إذا في أكثر من واحد.)

### 4) مايجريشن هذه الدفعة فقط

`php artisan migrate` الكامل قد يفشل على مايجريشن قديمة (`is_letter`). شغّل المسارات التالية بالترتيب:

```bash
php artisan migrate --force --path=database/migrations/2026_09_08_125500_add_is_for_specialists_to_plans_table.php
php artisan migrate --force --path=database/migrations/2026_09_08_130100_add_specialist_fields_to_users_table.php
php artisan migrate --force --path=database/migrations/2026_09_08_140000_add_patiant_count_to_plans_table.php
php artisan migrate --force --path=database/migrations/2026_09_08_200000_add_followed_specialist_id_to_users_table.php
```

إذا العمود موجود مسبقاً، المايجريشن تتخطاه بأمان (`hasColumn`).

### 5) كاش Laravel

```bash
php artisan optimize:clear
```

### 6) تحقق

```bash
php artisan tinker --execute="echo Schema::hasColumn('users', 'followed_specialist_id') ? 'ok' : 'MISSING';"
grep -n "APP_KEY" .env
```

`APP_KEY` لازم يبقى موجود. لو الملف فاضي أو ناقص، أرجع النسخة من `/root/nanteq.env.bak.*`.

لا تعمل `git add .env`. الملف في `.gitignore`.

---

## ملاحظة أمنية

إزالة التتبع تمنع رفع `.env` من الآن. النسخة القديمة ما زالت داخل أول commit في التاريخ. بعد الرفع يُفضّل تدوير أسرار الإنتاج (DB, mail, AI tokens) لأن من استنسخ الريبو قديماً قد يملك الملف.
