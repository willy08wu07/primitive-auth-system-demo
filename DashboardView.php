<?php

class DashboardView implements IView
{
    public function output(array $params = [])
    {
        $phpVersion = phpversion();
?>
<div class="form">
<?php
    echo "<p class='welcome'>{$params['validatorMsg']}</p>";
    echo "<p class='welcome'>伺服器 PHP 版本：$phpVersion</p>";
?>
    <form action="/" method="post">
        <input type="hidden" name="action" value="logOut" />
        <div>
            <button type="submit">登出</button>
        </div>
    </form>
</div>
<?php
    }
}
