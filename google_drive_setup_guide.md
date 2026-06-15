# Google Drive Integration - Complete Setup & Configuration Guide

Yeh guide aapko step-by-step batayegi ke kaise aap is Laravel project ko shuru se lekar end tak set up kar sakte hain aur Google Drive integration ko bina kisi error ke configure kar sakte hain. Isme Google Cloud Console ke credentials banane se lekar code updates tak sab kuch detail mein shamil hai.

---

## Table of Contents
1. [Core Concept: Service Account vs OAuth (Quota Issue)](#1-core-concept-service-account-vs-oauth-quota-issue)
2. [Step 1: Google Cloud Console Setup (OAuth Credentials)](#step-1-google-cloud-console-setup-oauth-credentials)
3. [Step 2: Google Cloud Console Setup (Service Account Credentials)](#step-2-google-cloud-console-setup-service-account-credentials)
4. [Step 3: Google Drive Parent Folder Setup & Sharing](#step-3-google-drive-parent-folder-setup--sharing)
5. [Step 4: Laravel Environment Configuration (.env)](#step-4-laravel-environment-configuration-env)
6. [Step 5: Database & Local Storage Setup Commands](#step-5-database--local-storage-setup-commands)
7. [Step 6: Authenticating Admin (Final Connection)](#step-6-authenticating-admin-final-connection)

---

## 1. Core Concept: Service Account vs OAuth (Quota Issue)
* **Problem:** Google Service Accounts ka default storage quota **0 bytes** hota hai. Agar aap direct service account se files upload karenge, to Google Drive API `storageQuotaExceeded (403)` error degi.
* **Solution:** Hum **Admin ke Personal Google Account** ki storage (jo 15 GB free milti hai) use karte hain. 
  - Hum pehle Admin ko OAuth ke zariye login karwate hain aur unka access/refresh token database (`google_drive_tokens` table) mein save karte hain.
  - Jab bhi koi student file ya profile photo upload karta hai, to system admin ke token ko load kar ke admin ke behalf par file upload karta hai.
  - Service Account ko hum sirf backup aur folder connectivity check karne ke liye use karte hain.

---

## Step 1: Google Cloud Console Setup (OAuth Credentials)

Admin ki storage access karne ke liye aapko OAuth client credentials banane honge:

1. **Google Cloud Console** par jayein: [https://console.cloud.google.com/](https://console.cloud.google.com/)
2. Ek naya project create karein (e.g., `Student-Application-System`).
3. **Enable APIs & Services** par click karein, search bar mein **Google Drive API** search karein aur use **Enable** karein.
4. **OAuth Consent Screen** configure karein:
   - **User Type:** Select **External**.
   - **App Information:** App ka naam aur apni email dalein.
   - **Scopes:** `/auth/drive` (Google Drive full access) scope ko add karein.
   - **Test Users:** Apni personal Gmail address (e.g., `your-email@gmail.com`) add karein jo aap testing ke liye use karenge.
   - **Publishing Status (IMPORTANT):** OAuth screen ko **"Production"** mode par publish karein. Agar yeh "Testing" mode mein rahegi to authorization ke waqt `403 access_denied` ya unauthorized app ka error aa sakta hai.
5. **Credentials Create Karein:**
   - **Create Credentials** -> **OAuth Client ID** select karein.
   - **Application Type:** Select **Web Application**.
   - **Authorized Redirect URIs:** Add `http://127.0.0.1:8000/google/callback` (Production ya live website ke liye callback URL live domain ke sath badal jaye ga).
6. **Client ID & Client Secret:** Download ya copy kar lein aur apne paas save karein.

---

## Step 2: Google Cloud Console Setup (Service Account Credentials)

Service account background verification ke liye use hota hai:

1. Google Cloud Console mein **Credentials** section par jayein.
2. **Create Credentials** -> **Service Account** select karein.
3. Service Account ko koi bhi naam dein (e.g., `drive-uploader`) aur create karein.
4. Naye bane service account par click kar ke **Keys** tab mein jayein.
5. **Add Key** -> **Create New Key** par click karein aur **JSON** select karein.
6. Ek JSON file download ho jayegi. Is file ko rename kar ke `credentials.json` rakhein.
7. Is file ko apne Laravel project ke is folder mein copy karein:
   `storage/app/google/credentials.json`
8. **Service Account Email Copy Karein:** Service account ki email address (e.g., `drive-uploader@project.iam.gserviceaccount.com`) copy kar lein.

---

## Step 3: Google Drive Parent Folder Setup & Sharing

Aap chahte hain ke tamam students ke uploads ek main folder ke andar hon:

1. Apne personal Google Drive account par jayein.
2. Ek naya folder create karein (e.g., `Student_Portal_Uploads`).
3. Folder ke andar enter hon aur browser ka address bar check karein. Folder ID URL ke end mein hoti hai:
   `https://drive.google.com/drive/u/0/folders/1jq8-g2gfhJ_x8F31LNwwRzWGSjaF8S0S`
   *Yahan folder ID `1jq8-g2gfhJ_x8F31LNwwRzWGSjaF8S0S` hai.*
4. **IMPORTANT STEP:** Is main folder par right-click karein -> **Share** par click karein, aur jo Service Account ki Email (jo Step 2 ke end mein copy ki thi) use paste kar ke use **Editor/Writer** permissions ke sath share karein. Is ke bina api folder find nahi kar sakegi.

---

## Step 4: Laravel Environment Configuration (.env)

Apni `.env` file ko open karein aur niche diye gaye parameters set karein:

```env
# Google Drive Settings
GOOGLE_CLIENT_ID=576750401391-dgusu3far62c6do9g8fak8rphcjejk50.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/google/callback

# Path to the JSON service account key
GOOGLE_APPLICATION_CREDENTIALS=storage/app/google/credentials.json

# Parent Folder ID on Google Drive
GOOGLE_DRIVE_FOLDER_ID=1jq8-g2gfhJ_x8F31LNwwRzWGSjaF8S0S
```

*Note: Code automated hai, agar aap `GOOGLE_DRIVE_FOLDER_ID` mein ghalti se poora URL bhi paste kar dein, tab bhi code automatically usme se ID extract kar lega.*

---

## Step 5: Database & Local Storage Setup Commands

Apne project ko initialize karne aur chalane ke liye commands run karein:

1. **Install Dependencies:**
   ```powershell
   composer install
   ```
2. **Generate Application Key:**
   ```powershell
   php artisan key:generate
   ```
3. **Database Migrations Run Karein:**
   *(Ensure table structure including sessions, users, student_profiles, applications, and google_drive_tokens is created)*
   ```powershell
   php artisan migrate
   ```
4. **Create Symbolic Link (For local images visibility):**
   ```powershell
   php artisan storage:link
   ```
5. **Start Dev Server:**
   ```powershell
   php artisan serve
   ```

---

## Step 6: Authenticating Admin (Final Connection)

1. Laravel project ke Admin account se login karein (jo `role = 'admin'` ho).
2. Apne dashboard ya `/google/redirect` route par visit karein.
3. Yeh aapko Google Account select karne ke screen par le jayega.
4. Apni wahi Gmail select karein jis par Drive folder aur cloud console configured hai.
5. Permssions approve karein.
6. Approve hone ke baad, Google aapko wapas admin dashboard par redirect kar dega aur backend par token refresh key database table (`google_drive_tokens`) mein save ho jayegi.

**Ab aapka project ready hai!** Jab bhi koi student register karega aur profile submit karega, Google Drive ke parent folder ke andar automatically us student ke naam aur ID ka folder ban jayega (e.g., `STU-2026-0001 - Bazigh Minhas`) aur file upload ho jayegi!
