# إعداد إشعارات طلبات الترقية (إيميل + تيليجرام)

## 1. الإيميل (لماذا ما بترسل؟)

الإيميل يتحفظ ويُرسل من السيرفر. إذا ما وصلك:

- **تأكد من إعدادات البريد في `.env`:**
  - `MAIL_MAILER=smtp` (مش `log`) عشان يرسل فعلياً
  - `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` حسب مزود البريد (Gmail, Mailtrap, إلخ)
- **إيميل الأدمن:** من لوحة التحكم → الإعدادات (Settings) → تأكد إن في قيمة لـ **Email** (هذا الإيميل اللي يوصله إشعار طلب الترقية). إذا فاضي، النظام يستخدم `MAIL_FROM_ADDRESS` من `.env`.
- إذا استخدمت `MAIL_MAILER=log` الإيميل ما بيروح على صندوق وارد، بيروح على الملف `storage/logs/laravel.log` فقط (للتجربة).

**مثال Gmail (مع App Password):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 2. تفعيل إشعار التليجرام (بدون تدخل اليوزر)

النظام يرسل إشعار **تيليجرام** من السيرفر للأدمن باستخدام **Telegram Bot API** (مجاني).

### الخطوات:

1. **إنشاء بوت تيليجرام (إذا لسه ما عملته):**
   - افتح تيليجرام وابحث عن **@BotFather**
   - أرسل `/newbot` واتبع التعليمات (اسم البوت + username ينتهي بـ `bot`)
   - BotFather هيرجعلك **Token** — احفظه

2. **إضافة التوكن في `.env` أولاً:**
   ```env
   TELEGRAM_BOT_TOKEN=التوكن_اللي_وصلك_من_BotFather
   ```

3. **الحصول على Chat ID:**
   - افتح تيليجرام وابحث عن البوت اللي أنشأته واضغط **Start** أو أرسل أي رسالة.
   - من مجلد المشروع شغّل:
     ```bash
     php artisan telegram:get-chat-id
     ```
   - الأمر يطبع الـ **Chat ID** ويقترح سطور `.env` الكاملة.

4. **إكمال `.env`:**
   أضف أو عدّل:
   ```env
   TELEGRAM_NOTIFY_ENABLED=true
   TELEGRAM_BOT_TOKEN=التوكن
   TELEGRAM_CHAT_ID=الرقم_اللي_طلع_من_الأمر
   ```

5. **مسح الكاش:**
   ```bash
   php artisan config:clear
   ```

بعد هيك، كل ما مستخدم يقدّم طلب ترقية، السيرفر يرسل إيميل للأدمن (إذا الإيميل مضبوط) ورسالة تيليجرام (إذا التليجرام مفعّل والـ Token و Chat ID مضبوطين). الرسالة تحتوي تفاصيل الطلب ورابط صفحة الطلب في لوحة الأدمن.
