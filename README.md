# plugin-test-support

## このプラグインについて
- このプラグインは、CakePHP3のプラグインのテスト支援用のプラグインです~~(ややこしい)~~
- プラグインのテストを記述するにあたって、通常のテストであれば、データベース接続にapp.phpにDatasourcesのtest内に記載をする必要がありますが、plugin単体でテストをしようとすると記載場所がない(はず)です。
- bakeのテストなどを拝見すると、mockを使ってテストしているようですが、実際のデータベースを使ってテストをしたいということでこんなプラグインを作成してみました。
- ~~正直こんなのなくても、同じことができそうな気もするんですが、どうなんですかね・・・~~

## 使い方
① テストしたいプラグインのcomposer.jsonに以下を記載し、update
```
{
    "require-dev": {
        "satthi/plugin-test-support": "dev-master"
    },
}

```
② 使用したいテストに以下を記載
```
<?php
namespace EncryptionSupport\Test\TestCase\Model\Table;

use App\Model\Table\AccountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
//以下をuse
use PluginTestSupport\Test\TestCase\AppTestCase;

/**
 * App\Model\Table\AccountsTable Test Case
 */
//AppTestCaseをextendsする
class AccountsTableTest extends AppTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.encryption_support.accounts',
    ];

    /**
     * setUpBeforeClass method
     * setUpBeforeClassをオーバーライドする
     * @return void
     */
    public static function setUpBeforeClass(){
        //データベースの接続情報
        $info = [
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
        parent::setConnectionInfo($info);
        
        parent::setUpBeforeClass();
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
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->loadSingle('Accounts');
    }
}
```
