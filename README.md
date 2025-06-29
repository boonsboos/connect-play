# connect & play

Connect en Play maakt gebruik van Docker Compose om de infrastructuur te beheren.

## runnen
```shell
docker compose up --build
```

Nu zullen de containers opstarten.

Als het de eerste keer is dat je deze opstart, moet je de database importeren.

Navigeer in je browser naar `localhost:8081` en log in.

Raadpleeg het beheerdocument §4.4 phpMyAdmin voor verdere informatie

## tests uitvoeren (na runnen)
De container die de unit tests uitvoert zit onder een andere profiel dan de rest.
Deze moet je dus apart opstarten.

In een nieuwe terminal: 
```shell
docker compose --profile test up --build
```

Nu worden de unit tests uitgevoerd.
Het kan zijn dat de editor een waarschuwing geeft over ontbrekende functies/klasses.
Dit klopt, want deze worden in de testcontainer geïnstalleerd.