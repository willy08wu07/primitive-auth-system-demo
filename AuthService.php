<?php

class AuthService
{
    /**
     * 查詢使用者是否正在執行登入流程。
     * @return bool
     */
    public function isLoggingIn(): bool
    {
        return isset($_POST['username']);
    }

    /**
     * 查詢使用者是否正在執行登出流程。
     * @return bool
     */
    public function isLoggingOut(): bool
    {
        return isset($_POST['action']) && $_POST['action'] === 'logOut';
    }

    /**
     * 查詢是否已登入。
     */
    public function isLoggedIn(): bool
    {
        return ! empty($_SESSION['username']);
    }

    /**
     * 輸出未登入的首頁（登入畫面）。
     * @param array $params
     */
    public function outputHomepage(array $params = []): void
    {
        $view = new StandardTemplateView(new LoginFormView());
        $view->output($params);
    }

    /**
     * 輸出登入後的首頁。
     * @param array $params
     */
    public function outputDashboard(array $params = []): void
    {
        $username = $_SESSION['username'];
        $view = new StandardTemplateView(new DashboardView());
        $view->output(array_merge($params, [
            'validatorMsg' => "歡迎 $username 登入！",
        ]));
    }

    /**
     * 執行登入或註冊的流程。
     * @param PDO $conn
     * @param string $username
     * @param string $password
     */
    public function logInOrRegister(PDO $conn, string $username, string $password): void
    {
        // 這裡暫不處理 session fixation 攻擊手法：<https://en.wikipedia.org/wiki/Session_fixation>
        // 驗證帳號、密碼的格式
        if ( ! preg_match('/^[a-z0-9_]{4,20}$/', $username)) {
            $this->outputHomepage([
                'validatorMsg' => '帳號必須以半形小寫英文、半形阿拉伯數字或半形底線組成，且須介於 4~20 字之間。',
            ]);
            return;
        }
        if ( ! preg_match('/^.{4,20}$/', $password)) {
            $this->outputHomepage([
                'validatorMsg' => '密碼須介於 4~20 字之間。',
            ]);
            return;
        }

        // 檢查是否已有這個帳號
        $statement = $conn->prepare('select * from users where username = :username limit 1');
        $statement->execute([
            'username' => $username,
        ]);
        $users = $statement->fetchAll();
        if (count($users) == 0) {
            // 沒有這個帳號，直接幫他註冊
            $this->register($conn, $username, $password);
            return;
        }

        // 有這個帳號，幫他檢查密碼是否正確
        $user = $users[0];
        if (sha1($user['password_salt_prefix'] . $password) !== $user['password_hash']) {
            // 不符合計算出的雜湊密碼，賞他個驗證失敗訊息
            $this->outputHomepage([
                'validatorMsg' => '密碼輸入錯誤。',
            ]);
            return;
        }

        // 驗證成功，進入會員專區
        $_SESSION['username'] = $username;
        $this->outputDashboard();
    }

    /**
     * 執行註冊的流程。
     */
    private function register(PDO $conn, string $username, string $password): void
    {
        $statement = $conn->prepare('insert into users (username, password_salt_prefix, password_hash)
            values (:username, :password_salt_prefix, :password_hash)');
        $passwordSaltPrefix = bin2hex(random_bytes(10));
        $saltedPassword = $passwordSaltPrefix . $password;
        $passwordHash = sha1($saltedPassword);
        $statement->execute([
            'username' => $username,
            'password_salt_prefix' => $passwordSaltPrefix,
            'password_hash' => $passwordHash,
        ]);
        $this->outputHomepage([
            'validatorMsg' => '註冊好新帳號了，請登入。',
        ]);
    }

    /**
     * 執行登出的流程。
     */
    public function logOut(): void
    {
        unset($_SESSION['username']);
        $this->outputHomepage();
    }
}
