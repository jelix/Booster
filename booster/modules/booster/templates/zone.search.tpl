

<div id="booster-search">
    {form $form, $submitAction, array('search' => true), 'html', array('method'=>'GET')}

            <div class="classic-search">
                
                {ctrl_label 'name'} :
                {ctrl_control 'name'}
                
                {ctrl_label 'tags'} :
                {ctrl_control 'tags'}
                
                {formsubmit}
           <button type="button" id="search-trigger" class="jforms-submit"><img src="/booster/images/add.png" alt="Click to use search"/>{@booster~main.search.advanced@}</button>
                
            </div>
        
        <div id="advanced-search">
            <div>
                {ctrl_label 'types'} <br/>
                {ctrl_control 'types'} <br/>
                {ctrl_label 'dev_status'}<br/>
                {ctrl_control 'dev_status'}

            </div>
           <div>
                {ctrl_label 'jelix_versions'} <br/>
                {ctrl_control 'jelix_versions'}
           </div>
            <div>
                {ctrl_label 'author_by'} {ctrl_control 'author_by'} <br/>
                {ctrl_control 'recommendation'} {ctrl_label 'recommendation'}
           </div>
        </div>
        
    {/form}

</div>
