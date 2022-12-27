(function(){
    $(document).ready(function(){
        let lastChanges = $('#jforms_booster_version_last_changes');
        if(lastChanges.length > 0){
            lastChanges.charCount({allowed : 255, warning : 25, css : 'charCounter'});
        }

        let imagePath = '/booster/images/';
        let adv_search = document.getElementById("advanced-search");

        // show search form
        $("#search-trigger").click(function () {
            $(this).toggleClass("active");
            adv_search.classList.toggle('opened');
            toggleImage($("#search-trigger"));
        });

        var flag = true;
        var toggleImage = function($el){
            if(flag){
                $el.find('img').attr({src:imagePath+"delete.png"});
                flag = false;
            }
            else{
                $el.find('img').attr({src:imagePath+"add.png"});
                flag = true;
            }
        }
    });
    
})();
