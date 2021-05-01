# Documentation

## Working with live preview

- Install Chef Habitat
- Install Habitat packages:

    ```bash
    sudo hab pkg install jarvus/mkdocs
    ```

- Create python environment:

    ```bash
    hab pkg exec jarvus/mkdocs python -m venv "./.venv"
    source "./.venv/bin/activate"
    pip install --upgrade pip
    pip install mkdocs mkdocs-material mkdocs-awesome-pages-plugin fontawesome_markdown
    ```

- Launch live preview server:

    ```bash
    mkdocs serve
    ```

- Open <http://localhost:8000/>
