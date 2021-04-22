# PostgreSQL

## Set up read only user

This user is manually created on the production PostgreSQL instance to provide read-only access to local development instances:

```sql
CREATE ROLE read_only LOGIN PASSWORD '{stored in bitwarden}';

GRANT CONNECT ON DATABASE laravel TO read_only;

GRANT USAGE ON SCHEMA public TO read_only;

GRANT SELECT ON ALL TABLES IN SCHEMA public TO read_only;
```

The actual password is stored in the BitWarden shared entry `read_only @ postgresql`
