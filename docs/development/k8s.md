# Kubernetes

1. Create a disposable local Kubernetes environment:

    ```bash
    kind create cluster
    ```

2. Install Helm chart:

    ```bash
    helm install letsplan ./helm-chart \
      --set workloads.app.secrets.app=letsplan-dev \
      --set images.app.repository=ghcr.io/jarvusinnovations/letsplanorg/laravel-site
    ```

    !!! warning

        Kind is incompatible with docker.pkg.github.com and the urbanSpatial organization does not have ghcr.io support enabled

        The above command overrides the release to use a ghcr.io-hosted image under Jarvus' organization. This image is not automatically updated when new versions are pushed/released on the urbanSpatial organization and must have updated container images manually pushed.

3. Generate `APP_KEY` secret and restart pod:

    ```bash
    APP_POD=$(kubectl get pod -l component=app -o jsonpath='{.items[0].metadata.name}')
    APP_KEY=$(kubectl exec -it $APP_POD -- php artisan key:generate --show --no-ansi)
    kubectl create secret generic letsplan-dev \
      --from-literal="APP_KEY=${APP_KEY}"
    kubectl delete pod $APP_POD
    ```

4. Forward port to access app:

    ```bash
    APP_POD=$(kubectl get pod -l component=app -o jsonpath='{.items[0].metadata.name}')
    kubectl port-forward pods/$APP_POD 8080:80
    ```

    Open <http://localhost:8080>

5. Populate database:

    Load a database dump, or edit the `letsplan` ConfigMap to connect to the online database instead of populating the local one and then restart the pod.

6. Generate tiles:

    Open <http://localhost:8080/mbtile> to generate tiles.
