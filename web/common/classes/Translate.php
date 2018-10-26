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
            'All_staff'         => 'Tot staff-ul',
            'Home'              => 'Acasa',
            'Rating'            => 'Evaluare',
            'Tables'            => 'Tabel-uri',
            'All_users'         => 'Staff',
            'All_employees'     => 'Muncitori',
            'Name'              => 'Nume',
            'Team'              => 'Echipa',
            'Depart'            => 'Departament',
            'Action'            => 'Actiune',
            'Employees_details' => 'Detalii muncitori',
            'Filters'           => 'Filtreaza',
            'Select_team'       => 'Selecteaza echipa',
            'This_field_required'   => 'Acest camp trebuie completat',
            'Select_Employees'  => 'Selecteaza muncitorul',
            'Select_year'       => 'Selecteaza anul',
            'Select_month'      => 'Selecteaza luna',
            'Submit'            => 'Aplica',
            'Data_for'          => 'Date pentru',
            'Bar'               => 'Grafic bare',
            'Line'              => 'Grafic linii',
            'Not_found_data'    => 'Nu s-au gasit date pentru aceasta cautare, slecteaza alte valori si reincearca.',
            'Dashboard'         => 'Dashboard',
            'Select_table'      => 'Selecteaza tabel',
            'Offices'           => 'Serviciu',
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
            'Team'              => 'Squadra',
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
            'Data_for'          => 'Data for',
            'Bar'               => 'Bar graph',
            'Line'              => 'Line graph',
            'Not_found_data'    => 'No data found. Please select other values and try again.',
            'Dashboard'         => 'Dashboard',
            'Select_table'      => 'Select table',
            'Offices'           => 'Offices',
        ];
    }

    private static function langIT()
    {
        return [
            'navNotification'   => 'DB e\' stata aggiornata',
            'navEmplData'       => 'Vedi dati dei lavoratori',
            'navStaffData'      => 'Vedi dati dei staff',
            'All_staff'         => 'Tutti i staff',
            'Home'              => 'Pagina principale',
            'Rating'            => 'Valutazione',
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
            'This_field_required'   => 'Questo campo e\' obbligatorio',
            'Select_Employees'  => 'Seleziona dipendente',
            'Select_year'       => 'Seleziona anno',
            'Select_month'      => 'Seleziona mese',
            'Submit'            => 'Invia',
            'Data_for'          => 'Dati per',
            'Bar'               => 'Grafico a barre',
            'Line'              => 'Grafico a linee',
            'Not_found_data'    => 'Non sono trovati i dati, cerca con altri valori.',
            'Dashboard'         => 'Curscotto',
            'Select_table'      => 'Seleziona tabelle',
            'Offices'           => 'Sottocommessa',
        ];
    }


    public static function getLang($lang)
    {
        if ($lang === 'ro') {
            return self::langRO();
        } elseif ($lang === 'en') {
            return self::langEN();
        } elseif ($lang === 'it') {
            return self::langIT();
        } else {
            return self::langEN();
        }
    }

    public static function t($lang, $string)
    {
        return !empty(self::getLang($lang)[$string]) ? self::getLang($lang)[$string] : $string;
    }


}