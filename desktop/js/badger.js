
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

$('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').on('change', function () {
    if ($(this).value() == 'reader') {
        $('.showreader').show();
        $('.showtag').hide();
        $('.showcode').hide();
    }

    if ($(this).value() == 'badge') {
        $('.showreader').hide();
        $('.showpinreader').hide();
        $('.showtag').show();
        $('.showcode').hide();
    }
    if ($(this).value() == 'code') {
        $('.showreader').hide();
        $('.showpinreader').hide();
        $('.showtag').hide();
        $('.showcode').show();
    }

});

$('.eqLogicAttr[data-l1key=configuration][data-l2key=modelReader]').on('change', function () {

    if ($('.eqLogicAttr[data-l1key=configuration][data-l2key=modelReader]').value() == 'wiegand2')
        $('.showpinreader').show();
    else
        $('.showpinreader').hide();

});




$('.stopIncludeState').on('click', function () {
    $('.stopIncludeState').hide();
    $('.startIncludeState').show();
    changeIncludeState(0);
});

$('.startIncludeState').on('click', function () {
    $('.startIncludeState').hide();
    $('.stopIncludeState').show();
    changeIncludeState(1);
});

function changeIncludeState(_state) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/badger/core/ajax/badger.ajax.php", // url du fichier php
        data: {
            action: "changeIncludeState",
            state: _state,
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
        }
    });
}

 function listBadges(  ) {

 }
 
 
 function addCmdToTable(_cmd) {
}