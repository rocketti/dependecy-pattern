# Dependency Pattern - Rocketti

<hr />

## Dependencia

- Laravel
- SQLite3

<hr />

## Testes:
- Laravel
    - 8.x ✔️
    - 7.x ❌
    - 6.x ❌
    - 5.x ❌

<hr />

## Como usar:

- instale o pacode na sua aplicação:
``` shell
    composer require rocketti/dependecy-pattern --dev
```
- add no `.env` da aplicação a chave com o nome da pasta onde vai ficar os arquivos de dependencia.
```shell
DEPENDENCY_FOLDER="Rocketti"
```
- Execute o comando abaixo para criar os arquivos:
```shell 
dp:create {class_name} {table_name} {--check}
```
Opção `--check` é para verificar se existe as pastas para colocar os arquivos dentro.