<?php

class Controller_Welcome extends Soter_Controller {

	public function do_show() {
		echo <<<html
		<form action="check.do">
		<img src="captcha.do"><input type="text" name="code"/><input type="submit" value="check">	
		</form>
html;
	}

	public function do_captcha() {
		Sr::sessionStart();
		$captcha = new Soter_Captcha();
		$captcha->create();
	}

	public function do_check() {
		Sr::sessionStart();
		if (Sr::session('captcha_code')&&strtolower(Sr::session('captcha_code')) == strtolower(Sr::getPost('code'))) {
			echo 'okay';
		} else {
			echo 'error';
		}
		unset($_SESSION['captcha_code']);
	}

	public function do_httptest() {
		$http = new Soter_Http();
		$http->setCookieFilePath(Sr::config()->getStorageDirPath() . '/cache/http.cookie');
		$http->get('http://gitcode.com/soter/tests/indexfortest.php/Welcome/show.do');
		echo <<<html
		<form action="httptest_submit.do">
		<input type="file" name="test"/>
		<img src="httptest_captcha.do"><input type="text" name="code"/><input type="submit" value="check">	
		</form>
html;
	}

	public function do_httptest_submit() {

		$http = new Soter_Http();
		$http->setCookieFilePath(Sr::config()->getStorageDirPath() . '/cache/http.cookie');
		$data['code'] = Sr::getPost('code');
		echo $http->get('http://gitcode.com/soter/tests/indexfortest.php/Welcome/check.do', $data);
	}

	public function do_httptest_captcha() {
		$http = new Soter_Http();
		$http->setCookieFilePath(Sr::config()->getStorageDirPath() . '/cache/http.cookie');
		header('Content-Type: image/jpeg');
		echo $http->get('http://gitcode.com/soter/tests/indexfortest.php/Welcome/captcha.do');
	}

	public function do_httptestupfile() {
		if (Sr::post()) {
			Sr::dump($_FILES, Sr::post());
		} else {
			$http = new Soter_Http();
			$data = array('test' => '@' . __FILE__,'testvalue'=>'123');
			$http->post('http://gitcode.com/soter/tests/indexfortest.php/Welcome/httptestupfile.do',$data);
			echo $http->data();
		}
	}

	public function do_index() {
		echo Sr::db()->select('cname')->from('user')
			->where(array('id <=' => 2, 'id <>' => 3))
			->where(array('id =' => 0), 'or')
			->where(array('id >=' => 0), 'or');
	}

	public function do_get() {
		echo 'get';
		echo Sr::session('test');
	}

	/**
	 * 测试notice异常
	 */
	public function do_exceptionNotice() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		echo $none;
	}

	/**
	 * 测试exception异常
	 */
	public function do_exceptionException() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		throw new Soter_Exception_500('throw test');
	}

	/**
	 * 测试异常托管,json输出
	 */
	public function do_exception() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		none();
	}

	/**
	 * 测试异常托管,html输出
	 */
	public function do_exceptionHtml() {
		Sr::config()->setExceptionHandle(new Exception_HandleTestHtml());
		none();
	}

	/**
	 * 测试异常托管,cli输出
	 */
	public function do_exceptionCli() {
		Sr::config()->setExceptionHandle(new Exception_HandleTestCli());
		none();
	}

	/**
	 * 测试异常托管
	 */
	public function do_exceptionHandle() {
		Sr::config()->setExceptionHandle(new Exception_Handle());
		none();
	}

	/**
	 * 测试异常LoggerWriter
	 */
	public function do_exceptionLoggerWriter() {
		Sr::config()->setShowError(false)->addLoggerWriter(new Logger_MyWriter());
		none();
	}

	/**
	 * 测试异常动态改变env ENV_DEVELOPMENT
	 */
	public function do_envTestDevelopment() {
		Sr::config()->setEnvironment(Sr::ENV_DEVELOPMENT);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试异常动态改变env ENV_TESTING
	 */
	public function do_envTestTesting() {
		Sr::config()->setEnvironment(Sr::ENV_TESTING);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试异常动态改变env ENV_PRODUCTION
	 */
	public function do_envTestProduction() {
		Sr::config()->setEnvironment(Sr::ENV_PRODUCTION);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试showError设置
	 */
	public function do_showErrorTest() {
		Sr::config()->setShowError(false);
		none();
	}

	/**
	 * 包内部加载配置测试
	 */
	public function do_configPackage() {
		echo Sr::factory('Misc')->config();
	}

	/**
	 * 加载类库
	 */
	public function do_library() {
		echo (Sr::library('Misc') instanceof Misc) ? '1' : 0;
	}

	/**
	 * 加载dao
	 */
	public function do_dao() {
		echo (Sr::dao('TestDao') instanceof Dao_TestDao) ? '1' : 0;
	}

	/**
	 * 加载business
	 */
	public function do_business() {
		echo (Sr::business('TestBusiness') instanceof Business_TestBusiness) ? '1' : 0;
	}

	/**
	 * 加载model
	 */
	public function do_model() {
		echo (Sr::model('TestModel') instanceof Model_TestModel) ? '1' : 0;
	}

	/**
	 * 加载functions
	 */
	public function do_functions() {
		Sr::functions('functions');
		echo myFunction();
	}

	/**
	 * 自动加载functions
	 */
	public function do_functionsAuto() {
		echo myFunctionAuto();
	}

	/**
	 * 传递参数给方法
	 */
	public function do_args($type = 'system', $id = '002') {
		echo $type . $id;
	}

	public function do_url() {
		echo Sr::url('Welcome/index.do');
	}

	public function do_urlArgs() {
		$data = array('a' => 'v', 'd' => 'd');
		echo Sr::url('Welcome/index.do', $data);
	}

	public function do_urlRewrite() {
		Sr::config()->setIsRewrite(true);
		echo Sr::url('Welcome/index.do');
	}

	public function do_urlRewriteArgs() {
		$data = array('a' => 'v', 'd' => 'd');
		Sr::config()->setIsRewrite(true);
		echo Sr::url('Welcome/index.do', $data);
	}

	public function do_urlPath() {
		echo Sr::urlPath('public');
	}

	public function do_urlPathRes() {
		echo Sr::urlPath('public/css/style.css', false);
	}

	public function do_viewLoad() {
		//Sr::dump(Sr::config()->getRoute()->getHmvcModuleName());
		Sr::view()->load('test_load');
	}

	public function do_viewLoadData() {
		$data['a'] = 'b';
		Sr::view()->load('test_load_data', $data);
	}

	public function do_viewLoadDataReturn() {
		$data['a'] = 'b';
		echo Sr::view()->load('test_load_data_return', $data, true);
	}

	public function do_viewSet() {
		Sr::view()->set('a', 'b');
		Sr::view()->load('test_load_data');
	}

	public function do_viewAdd() {
		Sr::view()->add('a', 'b');
		Sr::view()->load('test_load_data');
	}

	public function do_viewAddSet() {
		Sr::view()->set('a', 'b');
		Sr::view()->add('a', 'c');
		Sr::view()->load('test_load_data');
	}

	public function do_viewSetAdd() {
		Sr::view()->add('a', 'b');
		Sr::view()->set('a', 'c');
		Sr::view()->load('test_load_data');
	}

	public function do_db() {
//		$pdo = new PDO("mysql:dbname=test;host=127.0.0.1;port=3306", "root", "admin", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
//		$query = new SoterPDO($pdo);
//		$query->debug = true ;
//		$query->from('catalog')->execute();
//		foreach ($query as $row) {
//			echo "{$row['name']}\n";
//		}
		$config = array(
		    'driverType' => 'mysql',
		    'database' => 'test',
		    'tablePrefix' => '',
		    'debug' => true,
		    'slowQueryDebug' => true,
		    'slowQueryTime' => 100, //单位毫秒，1秒=1000毫秒
		    'slowQueryHandle' => new Soter_Database_SlowQuery_Handle_Default(),
		    'indexDebug' => true,
		    'minIndexType' => 'index',
		    'indexHandle' => new Soter_Database_Index_Handle_Default(),
		    'masters' => array(
			array(
			    'hostname' => '127.0.0.1',
			    'port' => 3306,
			    'username' => 'root',
			    'password' => 'admin'
			)
		    ),
		    'slaves' => array(
			array(
			    'hostname' => '127.0.0.1',
			    'port' => 3306,
			    'username' => 'root',
			    'password' => 'admin'
			)
		    )
		);
		$db = new Soter_Database_ActiveRecord($config);
//		$db->from('test', 'b')
//			->join(array('testb' => 'c'), 'b.id=c.id', 'left', '(', ')', 'dd')
//			->join('aaa', 'aaa.id=dd.id', 'left')
//			->select('a,bc,c.dd,max(' . $db->wrap('id') . '),c.ccc as bdc')
//			->where(array(
//			    'c.dd' => 'ds',
//			    'b.id' => null,
//			    'dd not' => array(1, 2, 3),
//			    'cc' => array(44, 55)
//				)
//			)
//			->groupBy('dd')
//			->groupBy('ad')
//			->orderBy('dd', 'asc')
//			->orderBy('aad', 'desc')
//			->orderBy('random()')
//			->having(array('count(' . $db->wrap('id') . ') >' => '0'), '(')
//			->having(array('count(' . $db->wrap('id') . ') >' => '0'), 'and', ')')
//			->limit(0, 30)
//			->where(array('c' => 'd'))
//		;
//
//		echo $db;
//		$db->execute('');
//		Sr::dump($db->from('account2')
//			->where(array('id >='=>1))
//			->select('*')
//			->execute(),$db->errorMsg());
//		try {
//			$db->begin();
//			$db->update('account',array('key'=>'555'))->where(array('id'=>1))->execute();
//			$db->update('current')
//				->set('test', '22', TRUE)
//				->execute();
//			$db->commit();
//			echo 'okay';
//		} catch (Exception $exc) {
//			$db->rollback();
//			echo $exc->getMessage();
//		}
//		 Sr::dump($db->updateBatch('tests',array(array('catalog_id'=>1,'parent_id'=>5),array('catalog_id'=>2,'parent_id'=>6)),'catalog_id')
//			 //->where(array('catalog_id'=>1))
//			 ->getSql(),$db->error());
//		Sr::dump($db->delete('test1',array('id >'=>2))->getSql());
//		Sr::dump($db->insert('test1',array('id'=>  rand(100,1000)))->getSql());
//		Sr::dump($db->insertBatch('tests',array(
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000)),
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000)),
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000))
//		    ))->getSql(),$db->lastId());
//		Sr::dump($db->replace('test1',array('id'=>  rand(100,1000)))->getSql());
//		Sr::dump($db->replaceBatch('tests',array(
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000)),
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000)),
//		    array('parent_id'=>  rand(1000, 10000),'name'=> rand(1000, 10000))
//		    ))->getSql(),$db->lastId());
		Sr::dump(
			Sr::db($config)
				->cache(60, 'user-2')
				->select('*')
				->from('test1')
				->where(array('pid >' => 2))
				->execute()
				->rows()
		);
	}

}
