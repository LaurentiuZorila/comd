<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/26/18
 * Time: 5:14 PM
 */

class Translate
{

    private static function langRO()
    {
        return [
            'navNotification'   => 'Baza de date a fost actualizata',
            'navEmplData'       => 'Vezi datele anagatilor',
            'navStaffData'      => 'Vezi datele stafului',
            'All_staff'         => 'Staff',
            'Home'              => 'Acasa',
            'Rating'            => 'Evaluare',
            'Tables'            => 'Tabel-uri',
            'All_users'         => 'Staff',
            'All_employees'     => 'Operatori',
            'Name'              => 'Nume',
            'Team'              => 'Echipa',
            'Depart'            => 'Departament',
            'Action'            => 'Actiune',
            'Employees_details' => 'Detalii operatori',
            'Filters'           => 'Filtreaza',
            'Select_team'       => 'Selecteaza echipa',
            'This_field_required'   => 'Acest camp trebuie este obligatoriu',
            'Select_Employees'  => 'Selecteaza operatoru',
            'Select_year'       => 'Selecteaza anul',
            'Select_month'      => 'Selecteaza luna',
            'Submit'            => 'Aplica',
            'Data'              => 'Date',
            'Bar'               => 'Bar',
            'Line'              => 'Linii',
            'Not_found_data'    => 'Nu s-au gasit date pentru aceasta cautare, slecteaza alte valori si reincearca.',
            'Dashboard'         => 'Dashboard',
            'Select_table'      => 'Selecteaza tabel',
            'Offices'           => 'Serviciu',
            'Total_user_absentees' => 'Total absente',
            'Total_user_furlough'  => 'Total concedii',
            'Total_user_unpaid' => 'Total zile neplatite',
            'Profile'           => 'Profil',
            'Total_employees'   => 'Total operatori',
            'Db_success'        => 'Baza de date a fost updatata cu succes.',
            'Db_error'          => 'Ceva nu a mers corect, reincearca',
            'Csv_extension'     => 'Fisierul trebuie sa aiba terminatia .CVS',
            'Update_user_profile' => 'Muta useri',
            'Select_leader'     => 'Selecteaza team leader',
            'New_depart'        => 'Noul departament',
            'Select_depart'     => 'Selecteaza departament',
            'New_office'        => 'Noul serviciu',
            'Select_office'     => 'Selecteaza serviciu',
            'Save'              => 'Salveaza',
            'Best_operator'     => 'Best operator',
            'Average'           => 'Media',
            'Update_db'         => 'Actualizeaza DB',
            'Make_attention'    => 'Atentie',
            'Close'             => 'Inchide',
            'File_no_header'    => 'Pentru cele mai bune rezultate utilizati modelul de file de mai jos.)',
            'Download_file_from'    => 'Descarca file-ul de aici',
            'File'              => 'File',
            'Update_employees_profile' => 'Actualizeaza OP',
            'Employees'         => 'Operatori',
            'Given_stars'       => 'Feedback dat',
            'Click_filter_for_data' => 'Apasa pe filtru pentru a cauta alte date',
            'Unpaid_h'          => 'Ore neplatite',
            'Days'              => 'Zile',
            'Day'               => 'Zi',
            'Profile_success_updated' => 'Profilul a fost actualizat cu succes',
            'Edit_profile'      => 'Modifica profilul',
            'FN'                => 'Nume',
            'LN'                => 'Prenume',
            'Username'          => 'Nume utilizator',
            'Pass'              => 'Parola',
            'Pass_again'        => 'Repeta parola',
            'Feedback_given'    => 'Multumesc pentru feedback-ul acordat',
            'Leads'             => 'Team Leader',
            'Total'             => 'Total',
            'furlough'          => 'Concediu',
            'unpaid'            => 'Zile neplatite',
            'absentees'         => 'Absente',
            'employees_data'    => 'Datele operatorilor',
            'update_data'       => 'Actualizeaza DB',
            'my_profile'        => 'Profilul meu',
            'logout'            => 'Delogheaza-te',
            'english'           => 'Engleza',
            'italian'           => 'Italiana',
            'romanian'          => 'Romana',
            'next_update_db'    => 'Urmatorul pas este sa introduci date in DB, apasa butonul de info din dreapta.',
            'all_required'      => 'Toate campurile sunt obligatorii',
            'validation_minimum'    => 'trebuie sa contina cel putin',
            'validation_max'    => 'trebuie sa contina maxim',
            'characters'        => 'caractere',
            'pass_no_match'     => 'Parolele nu corespund',
            'already_exists'    => 'deja exista',
            'only_letters'      => 'trebuie sa contina doar litere',
            'only_char'         => 'trebuie sa contina doar caractere',
            'feedback'          => 'Feedback',
            'unpaid_days'       => 'Zile neplatite',
            'quality'           => 'Calitate',
            'target'            => 'Productie',
            'give_feedback'     => 'Lasa-ne un feedback',
            'last_month_data_title'   => 'Vizlualizeaza ulimele date',
            'last_month_data_content' => 'Apasa icoana de mai jos pentru a vizualiza ultimele tale date',
            'view_data'         => 'Vezi date',
            'nav_update_profile'    => 'De aici poti modifica datele de utilizator (nume, prenume, username sau parola)',
            'nav_give_feedback' => 'Daca nu ai lasata inca un feedback, voteaza apasand butonul de mai jos',
            'well_done'         => 'Operatione efectuata cu success',
            'something_wrong'   => 'Sau gasit niste erori',
            'info'              => 'Info',
            'thk_feedback'      => 'Multumesc pentru feedback-ul acordat',
            'for'               => 'pentru',
            'for_this'          => 'pentru aceasta',
            'not_found'         => 'nu sau gasit date',
            'try_search_another'    => 'incearca sa cauti dupa alte date',
            'your_rating'       => 'Rating-ul tau',
            'show'              => 'Vezi',
            'percentage'        => 'procent',
            'Team_furlough'     => 'Concedii echipa',
            'Team_unpaid'       => 'Zile neplatite echipa',
            'Team_absentees'    => 'Absente echipa',
            'Team_medical'      => 'Medical echipa',
            'view_employees'    => 'Vezi operatori',
            'view_staff'        => 'Vezi staff',
            'view_employees_nav_details'        => 'Din aceasta sectiunde poti vizualiza toti operatorii care apartin departamentului tau',
            'view_employees_nav_data_details'   => 'Apasa icoana de mai jos pentru a vizualiza datele operatorilor pentru fiecare luna sau an',
            'view_staff_nav_details'            => 'Pentru a vizualiza tot staff-ul',
            'move_staff_nav'        => 'Daca vrei sa muti useri apasa sectiunea de mai jos',
            'update_profile_nav'    => 'Din acceasta sectiune poti modifica datele tale personale',
            'not_found_leads'   => 'Nu sau gasit team leaderi',
            'only_numbers'      => 'trebuie sa contina doar numere',
            'data_month_exists' => 'Pentru filterele alese in baza de date sau gasit date deja existente. <br /> Daca vrei sa faci update cu datele presente in file, bifeaza update si reincearca',
            'update_db_navbar'  => 'Din aceasta sectiune poti incarca datele operatorilor pentru ficare luna si pentru toate tablele',
            'new_pass'          => 'Noua parola',
            'current_pass'      => 'Parola actuala',
            'wrong_password'    => 'Parola introdusa este gresita, reincearca',
            'rules_not_allowed' => 'Your rules does\'t matches with validation',
            'add_user'          => 'Adauga useri',
            'create'            => 'Creaza user',
            'default_pass'      => 'Parola default este',
            'not_found_current_month' => sprintf('Pentru luna %s - %d nu sau gasit date', self::currentMonth(), date('Y')),
            'no_file_selected'  => 'Nu exista file',
            'click_here_to_upload'  => 'Apasa aici pentru a incarca un file',
            'login_again'       => 'Trebuie sa te logezi din nou, in cateva secunde vei fi redirectionat catre pagina de login',
            'medical'           => 'Medical',
            'Total_user_medical'   => 'Total medical',
            'Team_medical'      => 'Ziele medical echipa',
            'Calendar_info'     => 'Din acceasta sectiune poti cere zile de concediu. Daca zilele sunt acceptate aceste zile vor aparea aici',
            'Request_success'   => 'Cerereata a fost inregistrata cu succes',
            'event_deleted'     => 'Eventul a fost sters cu succes',
            'event_updated'     => 'Eventul a fost acctualizat',
            'event_deleted'     => 'Eventul a fost sters',
            'event_request'     => 'Cerere de',
            'all'               => 'Toate',
            'can_modify_request'    => 'Cererea nu poate fi modifcata, contacteaza leaderul tau pentru a face aceasta schimbare',
            'Pending'           => 'In asteptare',
            'Denied'            => 'Respinsa',
            'Accepted'          => 'Acceptata',
            'confirm_delete'    => 'Sigur vrei sa stergi aceasta cerere?',
            'Request_failed'    => 'Cererea ta nu a fost inregistrata',
            'new_event'         => 'O cerere este in asteptare',
            'event_response_success'    => 'Cererea ta a fost acceptata',
            'event_response_denied'     => 'Cererea ta a fost respinsa',
            'ascending_dates'   => 'Data de inceput trebuie sa fie mai mica ca data de sfarsit',
            'from'              => 'de la',
            'notification_not_found'    => 'Nu ai nici o notificare',
            'nav_give_calendar' => 'In aceasta sectiune poti vedere zilele tale de concediu sau concediu neplatit, poti cere zile de concediu iar cand vor fi acceptate sau refuzate vei primi o notificare',
            'upload'            => 'Incarca',
            'type_int'          => 'Datele din coloana QUANTITY din file-ul csv, trebuie sa fie de tip INT (integer/numeric)',
            'quantity'          => 'Cantitate',
            'month'             => 'Luna',
            'days'              => 'Zile',
            'not_correct_file'  => 'File-ul nu este corect, te rugam uploadeaza file-ul generat de aplicatie, il poti descarca apasand butonul INFO din dreapta',
            'year'              => 'Anul',
            'actions'           => 'Actiune',
            'print'             => 'Printeaza',
            'new_event_added'   => 'Un event a fost adaugat',
            'mark_as_read'      => 'Marcheaza totul ca citit',
            'one_month_event'   => 'Introdu in calendar cate un event pentru fiecare luna in parte!',
            'event_added'       => 'Eventul a fost introdus cu succes',
        ];
    }


    private static function langEN()
    {
        return [
            'navNotification'   => 'New data are added',
            'navEmplData'       => 'See employees data',
            'navStaffData'      => 'See staff data',
            'All_staff'         => 'All staff',
            'Home'              => 'Home',
            'Rating'            => 'Rating',
            'Table'             => 'Table',
            'All_users'         => 'All staff',
            'All_employees'     => 'All employees',
            'Name'              => 'Name',
            'Team'              => 'Team',
            'Depart'            => 'Department',
            'Action'            => 'Action',
            'Employees_details' => 'Employees details',
            'Filters'           => 'Filter',
            'Select_team'       => 'Select team',
            'This_field_required'   => 'This field are required',
            'Select_Employees'  => 'Select employee',
            'Select_year'       => 'Select year',
            'Select_month'      => 'Select month',
            'Submit'            => 'Submit',
            'Data'              => 'Data',
            'Bar'               => 'Bar',
            'Line'              => 'Line',
            'Not_found_data'    => 'No data found. Please select other values and try again.',
            'Dashboard'         => 'Dashboard',
            'Select_table'      => 'Select table',
            'Offices'           => 'Offices',
            'Total_user_absentees'  => 'Total absentees',
            'Total_user_furlough'   => 'Total vacation',
            'Total_user_unpaid' => 'Total unpaid',
            'Profile'           => 'Profile',
            'Total_employees'   => 'Total employees',
            'Db_success'        => 'Your data base are successfully updated.',
            'Db_error'          => 'Something is going wrong, please try again',
            'Csv_extension'     => 'Your file must have .CVS extension',
            'Update_user_profile' => 'Move users',
            'Select_leader'     => 'Select team leader',
            'New_depart'        => 'New department',
            'Select_depart'     => 'Select department',
            'New_office'        => 'New office',
            'Select_office'     => 'Select office',
            'Save'              => 'Save',
            'Best_operator'     => 'Best operator',
            'Average'           => 'Average',
            'Update_db'         => 'Update DB',
            'Make_attention'    => 'Attention',
            'Close'             => 'Close',
            'File_no_header'    => 'For best results, complete an upload DB with down example file.',
            'Download_file_from'    => 'Download file from here',
            'File'              => 'File',
            'Update_employees_profile' => 'Update employees',
            'Employees'         => 'Employee',
            'Given_stars'       => 'Given stars',
            'Click_filter_for_data' => 'Click on Filter button to search another data',
            'Unpaid_h'          => 'Unpaird hours',
            'Days'              => 'Days',
            'Day'               => 'Day',
            'Profile_success_updated' => 'Your profile is successfully updated',
            'Edit_profile'      => 'Edit profile',
            'FN'                => 'First name',
            'LN'                => 'Last name',
            'Username'          => 'Username',
            'Pass'              => 'Password',
            'Pass_again'        => 'Repeat password',
            'Feedback_given'    => 'Thanks for your feedback',
            'Leads'             => 'Team Leader',
            'Total'             => 'Total',
            'furlough'          => 'Vacation',
            'unpaid'            => 'Unpaid',
            'absentees'         => 'Absentees',
            'employees_data'    => 'Employees data',
            'update_data'       => 'Update DB',
            'my_profile'        => 'My profile',
            'logout'            => 'Logout',
            'english'           => 'English',
            'italian'           => 'Italian',
            'romanian'          => 'Romanian',
            'next_update_db'    => 'Now you need update your DB, click to info button from left side.',
            'all_required'      => 'All fields are required',
            'validation_minimum'    => 'must be a minimum of',
            'validation_max'    => 'must be a maximum of',
            'characters'        => 'characters',
            'pass_no_match'     => 'Passwords doesn\'t match',
            'already_exists'    => 'already exists',
            'only_letters'      => 'must contain only letters',
            'only_char'         => 'must contain only characters',
            'feedback'          => 'Feedback',
            'unpaid_days'       => 'Unpaid days',
            'quality'           => 'Quality',
            'target'            => 'Target',
            'give_feedback'     => 'Give us a feedback',
            'last_month_data_title'   => 'View last data',
            'last_month_data_content' => 'Click on icon below to view your last data',
            'view_data'         => 'View data',
            'nav_update_profile'    => 'From here you cand modify you account data (first name, last name, username or password)',
            'nav_give_feedback' => 'If you don\'t give you feedback, give you rating by clicking on button bellow',
            'well_done'         => 'Well done',
            'something_wrong'   => 'Something is going wrong',
            'info'              => 'Info',
            'thk_feedback'      => 'Thanks for your feedback',
            'for'               => 'for',
            'for_this'          => 'for this',
            'not_found'         => 'not data found',
            'try_search_another'    => 'try to search another data',
            'your_rating'       => 'Your rating',
            'show'              => 'Show',
            'percentage'        => 'percentage',
            'Team_furlough'     => 'Team vacation',
            'Team_unpaid'       => 'Team unpaid',
            'Team_absentees'    => 'Team absentees',
            'Team_medical'      => 'Team medical',
            'view_employees'    => 'View employees',
            'view_staff'        => 'View staff',
            'view_employees_nav_details'        => 'Click on icon below to view all employees belong your department',
            'view_employees_nav_data_details'   => 'Click on icon below to view all employees data for all years and moths',
            'view_staff_nav_details'            => 'Click on icon below to view all staff',
            'move_staff_nav'    => 'If you want to move one user click icon bellow',
            'update_profile_nav'    => 'Form here you can update your profile account',
            'not_found_leads'   => 'Not found leads',
            'only_numbers'      => 'must contain only numbers',
            'data_month_exists' => 'For this filters in DB results data, please check again your data. <br /> If you want to update DB with data from your file, please check update checkbox',
            'update_db_navbar'  => 'From this section you can update data base for your employees for each month and each table',
            'new_pass'          => 'New Password',
            'current_pass'      => 'Current password',
            'wrong_password'    => 'You have inserted worng password, try again',
            'rules_not_allowed' => 'Your rules does\'t matches with validation',
            'add_user'          => 'Add users',
            'create'            => 'Add user',
            'default_pass'      => 'Default password for user created',
            'not_found_current_month' => sprintf('Not found data for month %s - %d', self::currentMonth(), date('Y')),
            'no_file_selected'  => 'No file selected',
            'click_here_to_upload'  => 'Click here to upload you file',
            'login_again'       => 'You must to login again, in a few seconds you will be redirected to login page',
            'medical'           => 'Medical',
            'Total_user_medical'   => 'Total medical',
            'Team_medical'      => 'Team medical leaves',
            'Calendar_info'     => 'From here you can request leave days. If this are accepted this days will appear here',
            'Request_success'   => 'Your request has been successfully added',
            'event_deleted'     => 'Event is successfully deleted',
            'event_updated'     => 'Event is successfully updated',
            'event_deleted'     => 'Event is successfully deleted',
            'event_request'     => 'Request',
            'all'               => 'All',
            'can_modify_request'    => 'Your request can\'t be modify, please contact your lead for do this',
            'Pending'           => 'Pending',
            'Denied'            => 'Denied',
            'Accepted'          => 'Accepted',
            'confirm_delete'    => 'Do you really want to delete?',
            'Request_failed'    => 'Your request is not added',
            'new_event'         => 'New request in pending',
            'event_response_success'    => 'Your request has been accepted',
            'event_response_denied'     => 'Your request has been denied',
            'ascending_dates'   => 'Start date must be lowest as end date',
            'from'              => 'from',
            'notification_not_found'    => 'You don\'t have notification',
            'nav_give_calendar' => 'In this section you can see you vacation or unpaid days. <br /> You can request vacation or unpaid days form here and when your request is accepted or denied you will receive a notification',
            'calendar_info_lead'        => 'Here appear all request from your employees (accepted, denied or pending requests). <br /> From here you accept or delete request from you employees.',
            'upload'            => 'Upload',
            'type_int'          => 'Data from column QUANTITY from your csv file must have INT (integer/numeric) type',
            'quantity'          => 'Quantity',
            'month'             => 'Month',
            'days'              => 'Days',
            'not_correct_file'  => 'File is corrupted, please upload file generated by application, download it form right INFO button',
            'year'              => 'Year',
            'actions'           => 'Actions',
            'print'             => 'Print',
            'new_event_added'   => 'New event added',
            'mark_as_read'      => 'Mark all as read',
            'one_month_event'   => 'Insert event in calendar for each month separately!',
            'event_added'       => 'Event has been successfully added',
        ];
    }

    private static function langIT()
    {
        return [
            'navNotification'   => 'DB è stata aggiornata',
            'navEmplData'       => 'Vedi dati dei lavoratori',
            'navStaffData'      => 'Vedi dati dei staff',
            'All_staff'         => 'Tutti i staff',
            'Home'              => 'Pagina principale',
            'Rating'            => 'Feedback',
            'Table'             => 'Tabelle',
            'All_users'         => 'Staff',
            'All_employees'     => 'Dipendenti',
            'Name'              => 'Nome',
            'Team'              => 'Scuadra',
            'Depart'            => 'Dipartimento',
            'Action'            => 'Azione',
            'Employees_details' => 'Diplendenti dettagli',
            'Filters'           => 'Filtro',
            'Select_team'       => 'Seleziona la squadra',
            'This_field_required'   => 'Questo campo è obbligatorio',
            'Select_Employees'  => 'Seleziona dipendente',
            'Select_year'       => 'Seleziona anno',
            'Select_month'      => 'Seleziona mese',
            'Submit'            => 'Invia',
            'Data'              => 'Dati',
            'Bar'               => 'Barre',
            'Line'              => 'Linee',
            'Not_found_data'    => 'Non sono trovati i dati, cerca con altri valori.',
            'Dashboard'         => 'Cruscotto',
            'Select_table'      => 'Seleziona tabella',
            'Offices'           => 'Sottocommessa',
            'Total_user_absentees'  => 'Totale assente',
            'Total_user_furlough'   => 'Totale ferie',
            'Total_user_unpaid' => 'Totale permeso',
            'Profile'           => 'Profilo',
            'Total_employees'   => 'Totale dipendenti',
            'Db_success'        => 'Bb è stata correttamente aggiornata',
            'Db_error'          => 'La tua azione non e andata a buon fine, riprova',
            'Csv_extension'     => 'Il file deve avvere esstensione .CSV',
            'Update_user_profile' => 'Sposta utente',
            'Select_leader'     => 'Seleziona team leader',
            'New_depart'        => 'Nuovo dipartimento',
            'Select_depart'     => 'Seleziona dipartimento',
            'New_office'        => 'Nuova sottovommessa',
            'Select_office'     => 'Seleziona sottocommessa',
            'Save'              => 'Salva',
            'Best_operator'     => 'Best operatore',
            'Average'           => 'Media',
            'Update_db'         => 'Aggiorna DB',
            'Make_attention'    => 'Attenzione',
            'Close'             => 'Qiudi',
            'File_no_header'    => 'Per miglioi risultati uttiliza il file sotto indicato.',
            'Download_file_from'    => 'Scarica file da qui',
            'File'              => 'File',
            'Update_employees_profile' => 'Aggiorna dipendente',
            'Employees'         => 'Dipenteni',
            'Given_stars'       => 'Tuo feedback',
            'Click_filter_for_data' => 'Clicca filtro per cerccare altri dati',
            'Unpaid_h'          => 'Ore permeso',
            'Days'              => 'Giorni',
            'Day'               => 'Giorno',
            'Profile_success_updated' => 'Profilo è stato aggiornato',
            'Edit_profile'      => 'Modifica profilo',
            'FN'                => 'Cognome',
            'LN'                => 'Nome',
            'Username'          => 'Nome utente',
            'Pass'              => 'Parola',
            'Pass_again'        => 'Ripetere parola',
            'Feedback_given'    => 'Grazie per feedback',
            'Leads'             => 'Team Leader',
            'Total'             => 'Totale',
            'furlough'          => 'Ferie',
            'unpaid'            => 'Permeso',
            'absentees'         => 'Assenze',
            'employees_data'    => 'Dati operatori',
            'update_data'       => 'Aggiorna DB',
            'my_profile'        => 'Mio profilo',
            'logout'            => 'Esci',
            'english'           => 'Inglese',
            'italian'           => 'Italiano',
            'romanian'          => 'Rumeno',
            'next_update_db'    => 'Addesso deve aggionare DB, clicca sul INFO buttone presente in destra.',
            'all_required'      => 'Tutti i campi sono abbligatori',
            'validation_minimum'    => 'deve contenere minimo',
            'validation_max'    => 'deve contenere massimo',
            'characters'        => 'carattere',
            'pass_no_match'     => 'Le parole non corrispondono',
            'already_exists'    => 'già esiste',
            'only_letters'      => 'deve contenere solo lettere',
            'only_char'         => 'deve contenere solo carattere',
            'feedback'          => 'Feedback',
            'unpaid_days'       => 'Giorni permeso',
            'quality'           => 'Qualità',
            'target'            => 'Produtività',
            'give_feedback'     => 'Lascia il tuo feedback',
            'last_month_data_title'   => 'Vizializza ultimi dati',
            'last_month_data_content' => 'Cicca sul icone in basso per vissaulizzare gli utlimi tue dati',
            'view_data'         => 'Vedi i dati',
            'nav_update_profile'    => 'Da qui poi modificare i dati del tuo account (nome, cognome, nome utente o parola)',
            'nav_give_feedback' => 'Se non hai lasciato il tuo feedback, fai click soto per dare il tuo feedback',
            'well_done'         => 'Operazione effetuata con successo',
            'something_wrong'   => 'L\'operazione effetuata ritorna errori',
            'info'              => 'Informazione',
            'thk_feedback'      => 'Grazie per il tuo feedback',
            'for'               => 'per',
            'for_this'          => 'per il',
            'not_found'         => 'non sono trovati dati',
            'try_search_another'    => 'prova a cercare altri dati',
            'your_rating'       => 'Tuo rating',
            'show'              => 'Apri',
            'percentage'        => 'percentuale',
            'Team_furlough'     => 'Ferie squadra',
            'Team_unpaid'       => 'Permeso squadra',
            'Team_absentees'    => 'Assenze squadra',
            'view_employees'    => 'Vedi gli operatori',
            'view_staff'        => 'Vedi i staff',
            'view_employees_nav_details'        => 'Cicca sulla icona in basso per vissaulizzare tutti operatori per tuo dipartimento',
            'view_employees_nav_data_details'   => 'Cicca sullla icona in basso per vissaulizzare dati dei operatori per ogni messe e anno',
            'view_staff_nav_details'            => 'Cicca sulla icona in basso per vissaulizzare tutti i staff',
            'move_staff_nav'    => 'Se voi spostare un\'uttente clicca sull icone presente in basso',
            'update_profile_nav'    => 'Se voi cambiari gli tue dati clicca icona presente in basso',
            'not_found_leads'   => 'Non sono trovati team leader',
            'only_numbers'      => 'deve contenere solo i numeri',
            'data_month_exists' => 'Per questo filtro in DB risultano già dati presenti, fai una verifica e prova un\'altra volta. <br /> Se voi fare update con i dati presenti nel file, clicca sul checkbox aggiorna',
            'update_db_navbar'  => 'Da questa sezione poi aggiornare la DB per tutti operatori per tutte mese e per tutte tabelle',
            'new_pass'          => 'Nuova parola',
            'current_pass'      => 'Parola attuale',
            'wrong_password'    => 'Parola inserita risulta errata, prova un\'altra volta',
            'rules_not_allowed' => 'Your rules does\'t matches with validation',
            'add_user'          => 'Aggiungi uttenti',
            'create'            => 'Aggiungi utente',
            'default_pass'      => 'Parola di default è',
            'not_found_current_month' => sprintf('Per mese %s - %d non sono trovati dati', self::currentMonth(), date('Y')),
            'no_file_selected'  => 'Nesun file presente',
            'click_here_to_upload'  => 'Clicca qui per caricare il tuo file',
            'login_again'       => 'Deve fare il login di nuovo, in qualche secondi provederemo con uscita',
            'medical'           => 'Malattia',
            'Total_user_medical'   => 'Totale malattia',
            'Team_medical'      => 'Malattia squandra',
            'Calendar_info'     => 'Da questa sezione poi richiedere giorni di ferie. Se i giorni veranno accettate li poi vedere qui',
            'Request_success'   => 'La tua richiesta è stata inserita con successo',
            'event_deleted'     => 'Evento è stato cancellato',
            'event_updated'     => 'Evento è stato aggiornatto',
            'event_deleted'     => 'Evento è stato canccelato',
            'event_request'     => 'Richiesta di',
            'all'               => 'Tutte',
            'can_modify_request'    => 'Richiesta non puo essere cambiata, contatto  il tuo leader per fare questa',
            'Pending'           => 'In attesa',
            'Denied'            => 'Cancellata',
            'Accepted'          => 'Accettata',
            'confirm_delete'    => 'Sei sicuro che voi canccelare questa richiesta?',
            'Request_failed'    => 'La tua richiesta non e andata a buon fine',
            'new_event'         => 'Nuova richiesta in attesa',
            'event_response_success'    => 'La tua richiesta è stata accetata',
            'event_response_denied'     => 'La tua richiesta è stata respinta',
            'ascending_dates'   => 'Data di indizzio deve essere minore come data finale',
            'from'              => 'da',
            'notification_not_found'    => 'Non risultano nuotifiche',
            'nav_give_calendar' => 'Da questa sezione poi vusializzare i tuoi permesi ferie, poi richiedere ferie o permesso e quanto ci sono accetate sarai avisato',
            'calendar_info_lead'        => 'Qui sono tutti richieste da operatori (richieste accettate, cancellate o in attesa). Da questa sezione poi accetare o cancellare queste richieste',
            'upload'            => 'Carica',
            'type_int'          => 'Dati presenti nella collonna QUANTITY dal file devono essere di tipo INT (numero)',
            'quantity'          => 'Quantità',
            'month'             => 'Mese',
            'days'              => 'Giorni',
            'not_correct_file'  => 'File è daneggiato, aggiorna con il file generato dall\'applicatione, lo poi scaricare cliccando sul butone INFO',
            'year'              => 'Anno',
            'actions'           => 'Azione',
            'print'             => 'Stampa',
            'new_event_added'   => 'Nuovo evento inserito',
            'mark_as_read'      => 'Segna tutto come letto',
            'one_month_event'   => 'Inserisci richesta in calendario per ogni mese separatamente!',
            'event_added'       => 'Richiesta è stata inserita con successo',
        ];
    }

    /**
     * @param $lang
     * @return array
     */
    public static function getLang($lang)
    {
        switch ($lang) {
            case 'ro':
                return self::langRO();
                break;
            case 'en':
                return self::langEN();
                break;
            case 'it':
                return self::langIT();
                break;
            default:
                return self::langEN();
                break;
        }
    }

    /**
     * @param mixed $lang
     * @param $string
     * @param array $params
     * @return array|mixed|string
     */
    public static function t($string, $params = [])
    {
        if (Session::exists('lang')) {
            $lang = Session::get('lang');
        } else {
            $lang = 'en';
        }
        // If empty params return string from translation
        if (is_array($string)) {
            if (empty($params)) {
                return !empty(self::getLang($lang)[$string[0]]) ? self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]] : $string[0] . ' ' . $string[1];
            }
        } else {
            if (empty($params)) {
                return !empty(self::getLang($lang)[$string]) ? self::getLang($lang)[$string] : $string;
            }
        }

        // Check params
        foreach ($params as $k) {
            if (is_array($string)) {
//                switch ($k) {
//                    case 'ucfirst':
//                        return !empty(self::getLang($lang)[$string[0]]) ? ucfirst(self::getLang($lang)[$string[0]]) . ' ' . self::getLang($lang)[$string[1]] : $string[0] . ' ' . $string[1];
//                        break;
//                    case 'strtoupper':
//                        return !empty(self::getLang($lang)[$string[0]]) ? strtoupper(self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]]) : $string[0] . ' ' . $string[1];
//                        break;
//                    case 'strtolower':
//                        return !empty(self::getLang($lang)[$string[0]]) ? strtolower(self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]]) : $string[0] . ' ' . $string[1];
//                        break;
//                    default:
//                        return !empty(self::getLang($lang)[$string[0]]) ? self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]] : $string[0] . ' ' . $string[1];
//                        break;
//                }
                if ($k === 'ucfirst' && !empty(self::getLang($lang)[$string[0]])) {
                    return !empty(self::getLang($lang)[$string[0]]) ? ucfirst(self::getLang($lang)[$string[0]]) . ' ' . self::getLang($lang)[$string[1]] : $string[0] . ' ' . $string[1];
                } elseif ($k === 'strtoupper' && !empty(self::getLang($lang)[$string[0]])) {
                    return !empty(self::getLang($lang)[$string[0]]) ? strtoupper(self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]]) : $string[0] . ' ' . $string[1];
                } elseif ($k === 'strtolower' && !empty(self::getLang($lang)[$string[0]])) {
                    return !empty(self::getLang($lang)[$string[0]]) ? strtolower(self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]]) : $string[0] . ' ' . $string[1];
                } else {
                    return !empty(self::getLang($lang)[$string[0]]) ? self::getLang($lang)[$string[0]] . ' ' . self::getLang($lang)[$string[1]] : $string[0] . ' ' . $string[1];
                }
            } elseif (!is_array($string)) {
//                switch ($k) {
//                    case 'ucfirst':
//                        return !empty(self::getLang($lang)[$string]) ? ucfirst(self::getLang($lang)[$string]) : $string;
//                        break;
//                    case 'strtoupper':
//                        return !empty(self::getLang($lang)[$string]) ? strtoupper(self::getLang($lang)[$string]) : $string;
//                        break;
//                    case 'strtolower':
//                        return !empty(self::getLang($lang)[$string]) ? strtolower(self::getLang($lang)[$string]) : $string;
//                        break;
//                    default:
//                        return !empty(self::getLang($lang)[$string]) ? self::getLang($lang)[$string] : $string;
//                        break;
//                }
                if ($k === 'ucfirst' && !empty(self::getLang($lang)[$string])) {
                    return !empty(self::getLang($lang)[$string]) ? ucfirst(self::getLang($lang)[$string]) : $string;
                } elseif ($k === 'strtoupper' && !empty(self::getLang($lang)[$string])) {
                    return !empty(self::getLang($lang)[$string]) ? strtoupper(self::getLang($lang)[$string]) : $string;
                } elseif ($k === 'strtolower' && !empty(self::getLang($lang)[$string])) {
                    return !empty(self::getLang($lang)[$string]) ? strtolower(self::getLang($lang)[$string]) : $string;
                } else {
                    return !empty(self::getLang($lang)[$string]) ? self::getLang($lang)[$string] : $string;
                }
            }
        }
    }


    /**
     * @return string
     */
    public static function currentMonth()
    {
        return Common::numberToMonth(date('n') - 1, Session::get('lang'));
    }

}