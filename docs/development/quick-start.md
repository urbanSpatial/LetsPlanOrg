# Quick start

1. Initialize new `.env` file:

    ```bash
    cp .env.example .env
    ```

    !!! tip

        Change the web port from 7780 to something unsused if you have a port conflict.

        Change the MailHog web port from 8725 to something unsused if you have a port conflict.

        If you have another Postgres running on 5432, add a line to your .env like `FORWARD_DB_PORT=5433`

2. Configure `.env` to use online/remote database and map tiles so you can skip loading data and building tiles locally:

    ```bash
    MBTILE_URL="https://letsplan.live.k8s.jarv.us/urban/{z}/{x}/{y}.pbf"
    MIX_MBTILE_URL="${MBTILE_URL}"
    DB_HOST=WORKSTATION_LAN_IP
    DB_PORT=5433
    DB_USERNAME=read_only
    DB_PASSWORD=FROM_BITWARDEN
    ```

    The password is available in BitWarden under the entry `read_only @ postgresql`
    
    !!! note
        The above configuration points `DB_PORT` at **5433** because we'll be opening a tunnel from there to the online/remote database with the `kubectl port-forward` command in step 4.
        
        We use that port to avoid conflict with the local PostgreSQL instance step 5 will still spin up on the default port **5432**, but which we will be ignoring and not loading any data into.
        
    !!! note
        If using Windows + WSL2 + Docker, then `DB_HOST=host.docker.internal`.  All other steps with Windows + WSL2 + Docker should be the same.


3. Use Docker to install PHP dependencies:

    ```bash
    docker run -ti --rm -v $(pwd):/app -w /app composer:2.0 composer install
    ```

4. Open a tunnel from the online PostgreSQL database to local port `5433`:

    ```bash
    export KUBECONFIG=~/.kube/letsplan-deployer.yaml
    DB_POD=$(kubectl get pod -l component=database -o jsonpath='{.items[0].metadata.name}')
    kubectl port-forward pods/$DB_POD --address 0.0.0.0 5433:5432
    ```

    Download `letsplan-deployer.yaml` from the `letsplan-deployer @ jarvus-live-cluster` entry in BitWarden

5. Start local Docker environment with Laravel Sail:

    ```bash
    ./vendor/bin/sail up -d
    ```

6. Generate unique local app key:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7. Install frontend dependencies:

    ```bash
    ./vendor/bin/sail yarn install
    ```

8. Execute a frontend build:

    ```bash
    ./vendor/bin/sail yarn production
    ```
