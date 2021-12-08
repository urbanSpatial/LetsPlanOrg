# Planning Overlays

## Load data

1. Connect to Kubernetes cluster:

    ```bash
    export KUBECONFIG=~/.kube/jarvus-live-kubeconfig.yaml
    kubectl get nodes
    ```

2. Capture name of current database pod:

    ```bash
    DB_POD=$(kubectl -n letsplan get pod -l component=database -o jsonpath='{.items[0].metadata.name}')
    ```

3. Backup existing database:

    ```bash
    kubectl -n letsplan exec $DB_POD \
        -- bash -c 'pg_dumpall --clean -U${POSTGRES_USER}' \
        > /tmp/letsplan.sql
    ```

4. Capture name of current application pod:

    ```bash
    APP_POD=$(kubectl -n letsplan get pod -l component=app -o jsonpath='{.items[0].metadata.name}')
    ```

5. Copy new planning overlay CSV into application pod:

    ```bash
    kubectl -n letsplan cp \
        ~/Downloads/LetsPlanParcelData/planningOverlays.csv \
        $APP_POD:/srv/app/storage/app
    ```

6. Truncate existing planning overlay data:

    ```bash
    echo 'TRUNCATE TABLE planning_overlays' | kubectl -n letsplan exec -i $DB_POD -- psql -U admin laravel
    ```

7. Load planning overlay CSV into database:

    ```bash
    kubectl -n letsplan exec -it $APP_POD -- php artisan lp:import-planning_overlays planningOverlays.csv
    ```
