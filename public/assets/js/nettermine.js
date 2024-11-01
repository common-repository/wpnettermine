/*
(c)2001 Computer-L.A.N. GmbH, Fulda
info@computer-lan.de
www.computer-lan.de

me20010611
funktionen zur gültigkeitsprüfung von formulareingaben
##############################################################################
*/

/*
me20010611
prüft eine e-Mail Adresse auf Gültigkeit
*/
function blnIsEMail (strWert)
{
/* if ( (!strWert) || (strWert.indexOf("@") < 0) || (strWert.indexOf(".") < 0) || (strWert.indexOf(" ") >= 0) )

dp20010802
----------------------------------------------------------- */
var exp = /.+@.+\..+/;
if (!exp.test(strWert) || !strWert.search(/.*@.*@.*/))
//---------------------------------------------------------
    return false;
else
    return true;
}


/*
me20010611
überprüfung einer PLZ, wenn land = d, dann 5 stellig sonst nicht leer
*/
function blnIsPLZ (strPLZ, strLand)
{
if ( (!strPLZ) ||
        (
        strLand.toUpperCase() == "D") &&
            (
            (strPLZ.length != 5) ||
            (isNaN(strPLZ))
            )
        )
    return false;
else
    return true;
}


/*
me20010611
überprüft ein datum im format dd.mm.yy auf gültigkeit
und liefert bei gültigkeit ein datumsobject mit dem datum, bei
fehler ncihts zurück
*/
function dtmToDate (strWert)
{
var tag;
var monat;
var jahr;

if (!strWert) return;
if (strWert.length != 8) return;

tag = strWert.substr(0, 2);
monat = strWert.substr(3, 2) - 1;
jahr = strWert.substr(6, 2);

var dtmBuf = new Date("20" + jahr, monat, tag);

if (isNaN(dtmBuf))
    return;
else
    {
    if (
        (tag == dtmBuf.getDate()) &&
        (monat == dtmBuf.getMonth()) &&
        ("20" + jahr == dtmBuf.getFullYear())
        )
        return dtmBuf;
    else
        return;
    }
}


/*
me20010611
liefert die differenz in tagen zwischen zwei datumswerten
bei fehler oder ungültigen werten rückgabe nichts
*/
function lngDateDiff_Days (dtmVon, dtmBis)
{
if ((!dtmVon) || (!dtmBis)) return;

if ((dtmVon) && (dtmBis))
    return Math.round((dtmBis.getTime() - dtmVon.getTime()) / 1000 / 60 / 60 / 24);
else
    return;
}


/*
me20010611
fügt einem string der länge 1 eine führende 0 (null) hinzu
*/
function strAndNull (strWert)
{
if (strWert.length == 1)
    return "0" + strWert;
else
    return strWert;
}


/*
me20010611
gültigkeitsprüfungen für das aktuelle formular
*/
function blnValidateForm ()
{
    if (document.Formular.sdfBemerkung) {
        if (document.Formular.sdfBemerkung.value.length > 250)
            {
            alert ("Die Bemerkungen dürfen maximal 250 Zeichen lang sein.");
            document.Formular.sdfBemerkung.focus();
            return false;
            }
    }
    else if (document.Formular.sdfGruppe_Name1) {
        if (!document.Formular.sdfGruppe_Name1.value)
            {
            alert ("Bitte geben Sie den Namen ein.");
            document.Formular.sdfGruppe_Name1.focus();
            return false;
            }

        if (!blnIsPLZ (document.Formular.sdfGruppe_PLZ.value, document.Formular.sdfGruppe_Land.value))
            {
            alert ("Bitte geben Sie eine gültige PLZ ein.");
            document.Formular.sdfGruppe_PLZ.focus();
            return false;
            }

        if (!document.Formular.sdfGruppe_Ort.value)
            {
            alert ("Bitte geben Sie den Ort ein.");
            document.Formular.sdfGruppe_Ort.focus();
            return false;
            }

        if ( (!document.Formular.sdfGruppe_Telefon.value) || (document.Formular.sdfGruppe_Telefon.value.length < 3) )
            {
            alert ("Bitte geben Sie die Telefonnummer ein.");
            document.Formular.sdfGruppe_Telefon.focus();
            return false;
            }

        if (!blnIsEMail(document.Formular.sdfGruppe_eMail.value))
            {
            alert ("Bitte geben Sie eine gültige e-Mail Adresse ein.");
            document.Formular.sdfGruppe_eMail.focus();
            return false;
            }


        if ( (!document.Formular.sdfBetreuer_Anrede.options[document.Formular.sdfBetreuer_Anrede.selectedIndex].value) || (document.Formular.sdfBetreuer_Anrede.options[document.Formular.sdfBetreuer_Anrede.selectedIndex].value < 1) || (document.Formular.sdfBetreuer_Anrede.options[document.Formular.sdfBetreuer_Anrede.selectedIndex].value > 2) )
            {
            alert ("Bitte wählen Sie eine Anrede.");
            document.Formular.sdfBetreuer_Anrede.focus();
            return false;
            }

        if (!document.Formular.sdfBetreuer_Nachname.value)
            {
            alert ("Bitte geben Sie den Nachnamen ein.");
            document.Formular.sdfBetreuer_Nachname.focus();
            return false;
            }
    }
    else if (document.Formular.vonJahr) {
        var dtmVon = new Date();
        var dtmBis = new Date();
        var lngDiff;

        blnRet = true;

        dtmVon.setYear(document.Formular.vonJahr.options[document.Formular.vonJahr.selectedIndex].value);
        dtmVon.setMonth(document.Formular.vonMonat.options[document.Formular.vonMonat.selectedIndex].value-1);
        dtmVon.setDate(1);

        dtmBis.setYear(document.Formular.bisJahr.options[document.Formular.bisJahr.selectedIndex].value);
        dtmBis.setMonth(document.Formular.bisMonat.options[document.Formular.bisMonat.selectedIndex].value);
        dtmBis.setDate(0);

        document.Formular.bisTag.value = dtmBis.getDate();

        /* differenz von - bis 0 bis 100 tage? */
        lngDiff = lngDateDiff_Days (dtmVon, dtmBis);
        if ((!lngDiff) || (lngDiff < 0) || (lngDiff > 370))
            {
            alert ("Die Differenz zwischen Von-Datum und Bis-Datum darf maximal ein Jahr betragen.");
            document.Formular.bisMonat.selectedIndex = document.Formular.vonMonat.selectedIndex-1;
            document.Formular.bisJahr.selectedIndex = document.Formular.vonJahr.selectedIndex+1;
            document.Formular.bisMonat.focus();
            return false;
            }

        /* Anreisetag 1 bis 7? */
        var dtmBuf;
        dtmBuf = document.Formular.sdfAnreisetag.options[document.Formular.sdfAnreisetag.selectedIndex].value
        if ( (!dtmBuf) || (dtmBuf < 1) || (dtmBuf > 7) )
            {
            alert ("Bitte geben Sie den Anreisetag an.");
            document.Formular.sdfAnreisetag.focus();
            return false;
            }

        /* übernachtungen 0 bis 31? */
        if ( (!document.Formular.sdfUebernachtungen.value) || (document.Formular.sdfUebernachtungen.value < 0) || (document.Formular.sdfUebernachtungen.value > 31) )
            {
            alert ("Bitte geben Sie die Zahl der Übernachtungen an. Die Zahl muß zwischen 0 und 31 liegen.");
            document.Formular.sdfUebernachtungen.focus();
            return false;
            }

        /* personen 1 bis 999? */
        if ( (!document.Formular.sdfPersonen.value) || (document.Formular.sdfPersonen.value < 1) || (document.Formular.sdfPersonen.value > 999) )
            {
            alert ("Bitte geben Sie die Zahl der Personen an. Die Zahl muß zwischen 1 und 999 liegen.");
            document.Formular.sdfPersonen.focus();
            return false;
            }
    }

    return true;
} /* blnValidateForm */


function YearAutoChange () {
    var dtmVon = dtmToDate( "01." + strAndNull(document.Formular.vonMonat.value) + "." + document.Formular.vonJahr.value.substr(2,2) );
    var dtmTag = (document.Formular.bisMonat.value == 2) ? 28 : 30 ;
    var dtmBis = dtmToDate( dtmTag + "." + strAndNull(document.Formular.bisMonat.value) + "." + document.Formular.bisJahr.value.substr(2,2) );
    var lngDiff = lngDateDiff_Days (dtmVon, dtmBis);
    if (lngDiff < 0)
        document.Formular.bisJahr.selectedIndex = (document.Formular.vonJahr.selectedIndex == 4) ? 4 : document.Formular.vonJahr.selectedIndex+1;
}

function AutoCorrectDate () {
    var dtmVon = dtmToDate( "01." + strAndNull(document.Formular.vonMonat.value) + "." + document.Formular.vonJahr.value.substr(2,2) );
    var dtmTag = (document.Formular.bisMonat.value == 2) ? 28 : 30 ;
    var dtmBis = dtmToDate( dtmTag + "." + strAndNull(document.Formular.bisMonat.value) + "." + document.Formular.bisJahr.value.substr(2,2) );
    var lngDiff = lngDateDiff_Days (dtmVon, dtmBis);
    if (lngDiff < 0) {
        document.Formular.bisMonat.selectedIndex = document.Formular.vonMonat.selectedIndex;
        document.Formular.bisJahr.selectedIndex = document.Formular.vonJahr.selectedIndex;
    }
}