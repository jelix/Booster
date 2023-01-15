
$(document).ready(function(){

    let composerElementId = "jforms_boosteradmin_items_mod_item_composer_id"
    let IdElement = "jforms_boosteradmin_items_mod_id"
    //jforms_booster_items_item_composer_id

    let composerElt = document.getElementById(composerElementId);
    let composerCheckElt = document.getElementById('checkcomposerresult');
    let preFilled = (document.getElementById(IdElement).value !== '');
    composerElt.addEventListener('change', function(event){
        let composerPackage = composerElt.value;
        if (composerPackage == '') {
            composerCheckElt.textContent = '';
            composerCheckElt.classList.remove('checkok');
            composerCheckElt.classList.remove('checkerror');
            return;
        }
        let url = composerCheckElt.getAttribute('data-url');
        url += '?package='+composerPackage;
        fetch(url)
            .then((response) => response.json())
            .then(function(data){
                if ('error' in data) {
                    composerCheckElt.textContent = '❌ ' + data.error;
                    composerCheckElt.classList.add('checkerror');
                    composerCheckElt.classList.remove('checkok');
                }
                else {
                    composerCheckElt.textContent = '✅'; //✓
                    composerCheckElt.classList.remove('checkerror');
                    composerCheckElt.classList.remove('add');
                    if (!preFilled) {
                        let frm = jFormsJQ.getForm('jforms_boosteradmin_items_mod');
                        for (const ctrlName in data.values) {
                            frm.element.elements[ctrlName].value =  data.values[ctrlName];
                        }
                        preFilled = true;
                    }
                }
            });
    });


});