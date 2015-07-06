<?php
namespace PluginTestSupport\Test\TestCase;

use Cake\TestSuite\TestCase;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\Fixture\FixtureManager;
use Cake\Core\Configure;
use Cake\Cache\Cache;

/**
 * AppTestCase
 * テストのベース設定
 */
class AppTestCase extends TestCase
{
    static protected $_connectionInfo = [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Postgres',
        'persistent' => false,
        'host' => 'localhost',
        //'port' => 'nonstandard_port_number',
        'username' => 'postgres',
        'password' => '',
        'database' => 'cakephp_test',
        'encoding' => 'utf8',
        'timezone' => 'Asia/Tokyo',
        'cacheMetadata' => false,
        'quoteIdentifiers' => false,
    ];
    
    static protected $_appInfo = 'App';
    
    public static function setUpBeforeClass(){
        parent::setUpBeforeClass();
        //データベース接続設定
        $connectionInfo = self::$_connectionInfo;
        
        //cacheの設定はしていないためcacheを無効にする
        Cache::disable();
        
        //defaultは使わないがいないとエラーになるのでテストと同じものを設定する
        Configure::write('Datasources.default',$connectionInfo);
        Configure::write('Datasources.test',$connectionInfo);
        ConnectionManager::config(Configure::consume('Datasources'));
        
        //APPが定義されていないとエラーになるため。(逆に何か設定してあればとりあえず動くみたい？)
        if (!defined('APP')){
            define('APP', self::$_appInfo);
        }
    }

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        //fixtureManagerを呼び出し、fixtureを実行する
        $this->fixtureManager = new FixtureManager();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        //fixtureの終了
        $this->fixtureManager->shutdown();
        unset($this->fixtureManager);
        parent::tearDown();
    }
    
    /**
     * tearDownsetConnectionInfo method
     *
     * @return void
     */
    protected function setConnectionInfo($info){
        //接続情報を変更する
        self::$_connectionInfo = $info;
    }
    
    protected function setAppInfo($info){
        //APP情報を変更する
        self::$_appInfo = $info;
    }

}
