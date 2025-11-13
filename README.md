## hospital_reservations-api
### Lokalny rozwój

```
cp .env.example .env
make init
make run
```
Aplikacja będzie dostępna pod [localhost:63851](localhost:63851) oraz [http://hospital_reservations.localhost/](http://hospital_reservations.localhost/). Narzędzie do SMPT testowania [http://hospital_reservations-mailpit.localhost/](http://hospital_reservations-mailpit.localhost/). Jeśli nie masz jeszcze skonfigurowanego środowiska Traefik, postępuj zgodnie z instrukcjami z tego [repozytorium](https://github.com/AutomaticCraftingTable/traefik-environment).

#### Polecenia
Przed uruchomieniem któregokolwiek z poniższych poleceń należy uruchomić powłokę:

```
make shell
```

| Polecenie               | Zadanie                                       |
|:------------------------|:----------------------------------------------|
| `composer <command>`    | Composer                                      |
| `composer test`         | Uruchamia testy backendu                      |
| `composer analyse`      | Wykonuje analizę Larastan dla plików backendu |
| `composer cs`           | Lintuje pliki backendu                        |
| `composer csf`          | Lintuje i poprawia pliki backendu             |
| `php artisan <command>` | Polecenia Artisan                             |

#### Kontenery
| Usługa     | Nazwa kontenera          | Domyślny port hosta             |
|:-----------|:-------------------------|:--------------------------------|
| `app`      | `hospital_reservations-api-dev`           | 63851                           |
| `database` | `hospital_reservations-db-dev`            | 63853                           |
| `redis`    | `hospital_reservations-redis-dev`         | 63852                           |
| `mailpit`  | `hospital_reservations-mailpit-dev`       | 63854                           |


### Dokumentacja architektury projektu
- [Wizualizacja bazy danych](https://dbdocs.io/embed/2b2f5860e9afda4487f342359136dcbd/09cf598f70774c1aa9a302b7974c7ffd)
- [Dokumentacja API](https://hospital_reservations-api-doc.apidog.io)
