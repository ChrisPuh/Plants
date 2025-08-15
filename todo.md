## Layout
- [x] implemntiere weiter dein layout. 
- [x] das Layout sollte einheitlich sein, also überall gleich aussehen
- [x] das Layout sollte modern sein, also nicht altbacken aussehen
- [x] das Layout sollte ansprechend sein, also gut aussehen
- [x] das Layout sollte übersichtlich sein, also nicht zu viele Informationen auf einmal anzeigen
- [x] layout sollte ein theme haben, also einheitlich aussehen (gerne mit emerald touch)
- [x] theme in app.css machen und so dass auch flux components es nutzen können
- [x] realworld them mit typischen benennungen (primary color, secondary color, etc.) und nicht mit flux themen
- [x] dark mode sollte unterstützt werden
- [x] das Layout sollte responsive sein, also auf mobilen Geräten gut aussehen
- 

## Plant Requests
wenn Pending Plant Requests oder Pending contributions, dann
- in sidebar rechts ein badge mit der Anzahl der offenen Anfragen     <flux:navbar.item href="#" badge="Pro" badge:color="lime">Calendar</flux:navbar.item>
- mann sollte auch nicht einfach nur genehmigen sondern die möglichkeit haben fehlende Daten zu ergänzen

## Plant Contributions


## Plant Show
- [ ] auch wenn daten null sind sollte alles angezeigt werden nur halt mit einer bemerkung dass es keine daten gibt 
- [ ] wenn keine Daten vorhanden sind, dann sollte es eine Möglichkeit geben, die Daten zu ergänzen auch vom user als contribution


## Plant Daten

- [ ] die migration ist zu groß. brauchen am anfang nicht so viele daten. 
- möglichkeit:
  - name
  - image
  - categorien Enum! allowed sollte dynamisch aus enums-values() generiert werden generell überall im code und nicht hardcodet
  - typen enum! allowed sollte dynamisch aus enums-values() generiert werden generell überall im code und nicht hardcodet
  - botanischer name
  - beschreibung
- [ ] das thema requested nehmen wir erstmal raus. das ist zu viel aufwand und wir haben noch keine daten dafür
- [ ] PlantType sollte eine extra Tabelle sein, wo der name ein PlantTypeEnum ist da eine plant mehrere Typen haben kann
- [ ] 'field_name' in PlantContribution sollte ein object sein, das die Daten enthält, die der User ergänzt hat. Das sollte auch in der Migration so sein.
## images
- [ ] sollten wir spartie nutzen? https://spatie.be/docs/laravel-medialibrary/v11/introduction



räume dateien auf ordentliche ordner struktur 

