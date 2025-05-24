# TP10 - PHP Application with Supabase

This is a PHP application that uses Supabase as its backend database.

## Deployment Instructions

### Prerequisites
- Railway.app account
- Supabase project
- Git

### Deployment Steps

1. Install Railway CLI:
```bash
npm i -g @railway/cli
```

2. Login to Railway:
```bash
railway login
```

3. Initialize Railway project:
```bash
railway init
```

4. Link your repository:
```bash
railway link
```

5. Add environment variables in Railway dashboard:
- SUPABASE_URL
- SUPABASE_KEY
- SUPABASE_SECRET
- APP_ENV
- APP_DEBUG

6. Deploy the application:
```bash
railway up
```

### Local Development

1. Clone the repository
2. Copy `.env.example` to `.env` and fill in your Supabase credentials
3. Install dependencies:
```bash
composer install
```

4. Start the development server:
```bash
php -S localhost:8000
```

## Database Schema

The application uses the following tables in Supabase:

- guerrier
- user
- other_tables...

For detailed schema information, refer to `supabase_init.sql`. 