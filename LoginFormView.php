<?php

include_once 'IView.php';

class LoginFormView implements IView
{
    public function output(array $params = [])
    {
?>
<div class="form">
<?php
        if (isset($params['validatorMsg'])) {
            echo "<p class='warning'>{$params['validatorMsg']}</p>";
        } else {
            echo '<p class="welcome">嗨～你好！<br/>請註冊或登入系統。</p>';
        }
?>
    <form action="/" method="post">
        <div>
            <label>
                <input type="text" name="username" placeholder="帳號" maxlength="20" />
            </label>
        </div>
        <div>
            <label>
                <input type="password" name="password" placeholder="密碼" maxlength="20" />
            </label>
        </div>
        <div>
            <button type="submit">註冊或登入</button>
        </div>
    </form>
</div>
<?php
    }
}
