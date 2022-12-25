<div id="login-box">
    {ifuserconnected}
    {$login} -
        <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@jcommunity~login.login.account@}</a>
        -
        <a href="{jurl 'jcommunity~login:out'}">{@jcommunity~login.logout@}</a>
    {else}
        <a href="{jurl 'jcommunity~login:index'}">{@jcommunity~login.form.submit@}</a>
        {if $canRegister} - <a href="{jurl 'jcommunity~registration:index'}">{@jcommunity~login.startpage.account.create@}</a>{/if}
    {/ifuserconnected}
</div>
