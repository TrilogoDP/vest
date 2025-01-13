<?php
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

class ControllerExtensionCaptchaRecaptchaEnterprise extends Controller {
    public function index() {
        // Завантажуємо потрібні мовні змінні, якщо потрібно
        $this->load->language('extension/captcha/recaptcha_enterprise');

        // Виводимо скрипт, який отриманий із reCAPTCHA Enterprise
        // А також додаємо <input type="hidden" name="g-recaptcha-response" /> або щось подібне
        // наприклад, у TWIG-шаблоні. Припустимо, що ви це робите у view:
        return $this->load->view('extension/captcha/recaptcha_enterprise', []);
    }

    public function validate() {
        // Тут ми перевіряємо отриманий токен
        if (isset($this->request->post['g-recaptcha-response'])) {
            $token = $this->request->post['g-recaptcha-response'];
        } else {
            return 'No token'; // або пусте значення
        }

        // Підключаємо автозавантаження (якщо воно ще ніде не підключене)
        require_once(DIR_SYSTEM . '/../vendor/autoload.php'); 
        // Якщо у вас інший шлях - підкоригуйте

        // Тепер можна викликати функцію create_assessment
        $this->createAssessment(
            $this->config->get('recaptcha_enterprise_site_key'),  // ваш ключ
            $token,                                               // отриманий з форми
            $this->config->get('recaptcha_enterprise_project_id'),// ваш projectId
            'submit'                                              // action
        );
    }

    // Можна винести логіку createAssessment в окремий метод
    private function createAssessment($recaptchaKey, $token, $project, $action) {
        // Створюємо клієнт
        $client = new RecaptchaEnterpriseServiceClient();
        $projectName = $client->projectName($project);

        // Налаштовуємо Event
        $event = (new Event())
            ->setSiteKey($recaptchaKey)
            ->setToken($token);

        // Створюємо Assessment
        $assessment = (new Assessment())
            ->setEvent($event);

        try {
            $response = $client->createAssessment($projectName, $assessment);

            // Перевіряємо дійсність токена
            if ($response->getTokenProperties()->getValid() == false) {
                $reason = $response->getTokenProperties()->getInvalidReason();
                // Можна повертати повідомлення або кидати помилку
                return 'Token invalid: ' . InvalidReason::name($reason);
            }

            // Перевіряємо Action
            if ($response->getTokenProperties()->getAction() === $action) {
                // Отримуємо score
                $score = $response->getRiskAnalysis()->getScore();
                // Дивимось, наскільки він низький/високий
                if ($score < 0.5) {
                    // Бот
                    return 'Low score: ' . $score;
                } else {
                    // Успіх
                    return 'OK, score: ' . $score;
                }
            } else {
                return 'Wrong action name';
            }
        } catch (\Exception $e) {
            return 'CreateAssessment() failed: ' . $e->getMessage();
        }
    }
}
