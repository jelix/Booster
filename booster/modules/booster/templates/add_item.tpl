<div id="article">
    <h1>{@booster~main.add.an.item@}</h1>
    {form $form, 'booster~default:saveItem', array(), 'html', array(
    'widgetsAttributes' => [ 'image' => [ 'baseURI'=> $j_basepath.'images-items/']]
    )}
    <table class="jforms-table">
        <tr>
            <td>{ctrl_label 'name'}</td><td>{ctrl_control 'name'}</td>
            <td>{ctrl_label 'item_composer_id'}</td><td>{ctrl_control 'item_composer_id'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'type_id'}</td><td>{ctrl_control 'type_id'}</td>
            <td>{ctrl_label 'tags'}</td><td>{ctrl_control 'tags'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'dev_status'}</td><td>{ctrl_control 'dev_status'}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>{ctrl_label 'slogan'}</td>
            <td colspan="3">{ctrl_control 'slogan'}</td>
        </tr>
        <tr>
            <td colspan="4">{ctrl_label 'short_desc'}</td>
        </tr>
        <tr>
            <td colspan="4">{ctrl_control 'short_desc'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'slogan_fr'}</td>
            <td colspan="3">{ctrl_control 'slogan_fr'}</td>
        </tr>
        <tr>
            <td colspan="4">{ctrl_label 'short_desc_fr'}</td>
        </tr>
        <tr>
            <td colspan="4">{ctrl_control 'short_desc_fr'}</td>
        </tr>   
        <tr>
            <td>{ctrl_label 'author'}</td><td colspan="3">{ctrl_control 'author'}</td>
        </tr> 
        <tr>
            <td>{ctrl_label 'url_repo'}</td><td colspan="3">{ctrl_control 'url_repo'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'url_website'}</td><td colspan="3">{ctrl_control 'url_website'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'url_download'}</td><td colspan="3">{ctrl_control 'url_download'}</td>
        </tr>
        <tr>
            <td>{ctrl_label 'image'}</td><td colspan="3">{ctrl_control 'image'}</td>
        </tr>
    </table>
    <div> {formsubmit} </div>
    {/form}
    {include 'booster~zone.syntax_wiki'}
</div>