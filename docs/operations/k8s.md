# Kubernetes

## Using deployer-admin service account

1. Download the `letsplan-deployer.yaml` attachment from the `letsplan-deployer @ jarvus-live-cluster` secret in BitWarden into your `~/.kube` directory
2. Activate the downloaded `KUBECONFIG` in your current terminal session:

    ```bash
    export KUBECONFIG=~/.kube/letsplan-deployer.yaml
    ```

3. Get the name of the currently running pods and store them in shell variables:

    ```bash
    APP_POD=$(kubectl get pod -l component=app -o jsonpath='{.items[0].metadata.name}')
    DB_POD=$(kubectl get pod -l component=database -o jsonpath='{.items[0].metadata.name}')
    ```

### Open interactive app shell

```bash
kubectl exec -it $APP_POD -- bash
```

### Open interactive database shell

```bash
kubectl exec -it $DB_POD -- psql -U admin laravel
```

### Run an artisan command

```bash
kubectl exec -it $APP_POD -- php artisan migrate
```

### Run an SQL query

```bash
echo 'SELECT * FROM users' | kubectl exec -i $DB_POD -- psql -U admin laravel
```

### Forward PostgreSQL port

```bash
kubectl port-forward pods/$DB_POD 5432:5432
```

!!! tip "Database logins"
    Default database credentials can be found in `helm-chart/values.yaml`
