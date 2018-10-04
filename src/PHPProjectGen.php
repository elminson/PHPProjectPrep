<?php
/**
 * Created by PhpStorm.
 * User: elminsondeoleobaez
 * Date: 10/3/18
 * Time: 6:00 PM
 */

namespace Elminson\PHPProjectGen;

class PHPProjectGen
{
    private $composer_config;

    /**
     * PHPProjectGen constructor.
     * @param $composer_config
     */
    public function __construct()
    {
        $this->getConfig();
    }

    /**
     *
     */
    public function GenerateProject()
    {
        $this->generateComposer();
        $this->generateClass();
        $this->generateTestCases();
    }

    private function getConfig()
    {
        $string = file_get_contents("config.json");
        $this->composer_config = json_decode($string, true);
    }

    /**
     *
     */
    private function generateComposer()
    {

        $composer_data = [];
        $composer_data['name'] = strtolower($this->composer_config['name'] . "/" . $this->composer_config['projectname']);
        $composer_data['description'] = $this->composer_config['description'];
        $composer_data['type'] = $this->composer_config['type'];
        if ($this->composer_config['phpunit']) {
            $composer_data['require']['phpunit/phpunit'] = '^7.4@dev';
            $composer_data['require-dev']['phpunit/phpunit'] = '^7.4@dev';
        }
        $composer_data['license'] = $this->composer_config['license'];
        $composer_data['authors'][0]['name'] = $this->composer_config['developer'];
        $composer_data['authors'][0]['email'] = $this->composer_config['email'];
        $src = $this->composer_config['name'] . "\\\\" . $this->composer_config['projectname'];
        $tests = $this->composer_config['name'] . "\\\\" . $this->composer_config['projectname'] . "\\\\Test";
        $composer_data['autoload']['psr-0'] = [
          $src => "src/",
          $tests => "tests/"
        ];
        $composer_data['autoload']['psr-4'] = [
          $src => "src/",
          $tests => "tests/"
        ];
        $composer_data['autoload-dev']['psr-4'] = [
          $src => "src/",
          $tests => "tests/"
        ];
        $composer_data['minimum-stability'] = $this->composer_config['minimum-stability'];
        $string = stripslashes(json_encode($composer_data, JSON_PRETTY_PRINT));
        $this->writeFile('composer', $string, "", "json");
    }

    private function generateClass()
    {
        $string = file_get_contents("src/ProjectTemplate.php");
        $name = $this->composer_config['name'] . '\\' . $this->composer_config['projectname'];
        $string = str_replace('{!namespace!}', $name, $string);
        $string = str_replace('{!class!}', $this->composer_config['projectname'], $string);
        $string = str_replace('{!email!}', $this->composer_config['email'], $string);
        $string = str_replace('{!date!}', date('m/d/Y'), $string);
        $string = str_replace('{!time!}', date('h:i A'), $string);
        $this->writeFile($this->composer_config['projectname'], $string);
    }

    private function generateTestCases()
    {
        $string = file_get_contents("src/testProjectTemplate.php");
        $name = $this->composer_config['name'] . '\\' . $this->composer_config['projectname'];
        $string = str_replace('{!namespace!}', $name, $string);
        $string = str_replace('{!projectname!}', $this->composer_config['projectname'], $string);
        $string = str_replace('{!loweclass!}', strtolower($this->composer_config['projectname']), $string);
        $this->writeFile($this->composer_config['projectname'], $string, 'test');

    }

    private function writeFile($name, $data, $prefix = "", $ext = "php")
    {
        $fp = fopen('src/temp/' . $prefix . $name . '.' . $ext, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }

}