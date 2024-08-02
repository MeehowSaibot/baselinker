# baselinker
Aplikacja napisana w PHP 8.1.9. Testowałem lokalnie na development serverze PHP.

Komentarze:

1. SSL został wyłączony do celów developmentu w parametrach cURL w funkcji POST.
   Linijki do usunięcia jeżeli chcemy SSL włączyć oznaczone w pliku sprintService.php w funkcji 'post' komentarzem:
   '//SSL off'

2. Brak dokładnych informacji odnośnie ErrorLevel = 1. Nie byłem pewien jak obsłużyć tymczasowo tworzone etykiety,
   więc po prostu pokazuję tymczasową etykietę na której jest widoczny błąd.

3. Po wygenerowaniu etykiety zauważyłem, że przez brak podanego państwa uderzając do 'OrderShipment' tworzy się
   etykieta z Holandii, zamiast Polski. Po dodaniu, natomiast, państwa wyskakuje błąd na ZIP'ie mimo podania prawidłowego
   kodu pocztowego podanego w wymaganiach. Zostawiłem więc dane tak jak były podane w wymaganiach.

Chętnie skonsultuje to zadanie w przypadku wyrażenia chęci na dalszy etap rekrutacji.