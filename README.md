# Dependency Pattern - Rocketti

<hr />

## Dependencia

- Laravel
- SQLite3

<hr />

## Testes:
- 10.x ⚠️ - ⟳ - Testando
- 9.x ✔️
- 8.x ✔️
- 7.x ✔️
- 6.x ✔️
- 5.x ✔️

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
## versões < 1.5.1 
dp:create {class_name} {table_name} {--check}

## versões >= 1.5.1 
dp:file {class_name} {table_name} {--check} {--update}
```
Opção `--check` é para verificar se existe as pastas para colocar os arquivos dentro.
Opção `--update` é para atualizar os arquivos


### Detalhes de testes:

- 10.x ⚠️ - ⟳ - Testando
     <!-- - Testado na `v9.17.0` do Laravel;
     - Testado na imagem docker PHP : `php:8.1.6-fpm-alpine`; -->
- 9.x ✔️
     - Testado na `v9.17.0` do Laravel;
     - Testado na imagem docker PHP : `php:8.1.6-fpm-alpine`;
- 8.x ✔️
     - Testado na `v8.83.14` do Laravel;
     - Testado na imagem docker PHP : `php:8.1.6-fpm-alpine`;
- 7.x ✔️
    - Testado na `v7.30.6` do Laravel;
    - Testado na imagem docker PHP : `php:7.2.5-fpm-alpine`;
- 6.x ✔️
    - Testado na `v6.20.44` do Laravel;
    - Testado na imagem docker PHP : `php:7.2.5-fpm-alpine`;
- 5.x ✔️
    -  Testado na `v5.8.38` do Laravel;
    - Testado na imagem docker PHP : `php:7.2.5-fpm-alpine`;

### Desenvolvimento

Teste: `php vendor/bin/phpunit --coverage-clover build/logs/clover.xml`