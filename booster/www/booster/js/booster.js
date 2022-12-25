(function(){
    $(document).ready(function(){
        let lastChanges = $('#jforms_booster_version_last_changes');
        if(lastChanges.length > 0){
            lastChanges.charCount({allowed : 255, warning : 25, css : 'charCounter'});
        }
    
    });
    
})();