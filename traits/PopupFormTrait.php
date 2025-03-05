<?php

namespace Wpjscc\PopupForm\Traits;

use Winter\Storm\Router\Helper as RouterHelper;
use System\Classes\PluginManager;
use Winter\Storm\Exception\ApplicationException;
use Str;
use File;
use App;
use Request;
use Config;

trait PopupFormTrait
{

    public function create_onSave($context = null)
    {

        $r = $this->asExtension('FormController')->create_onSave($context);
        if (post('create_form') !== null) {
            $_r = $this->_refurnResponse();
            if ($_r) {
                $r = $_r;
            }
        }

        return $r;
    }

    public function update_onSave($recordId = null, $context = null)
    {
        $r = $this->asExtension('FormController')->update_onSave($recordId, $context);

        if (post('update_form') !== null) {
            $_r = $this->_refurnResponse();
            if ($_r) {
                $r = $_r;
            }
        }

        return $r;
    }

    protected function _refurnResponse()
    {
        $r = [];

        //if (!(post('refresh_list') || post('refresh_relation'))) {
        //    return $r;
        //}

        $controller = $this->_runControllerMethod();

        if (post('refresh_list')) {
            $lists = explode(',', post('refresh_list'));
            foreach ($lists as $list) {
                $r = array_merge($r, $controller->listRefresh($list));
            }
        } else {
            if (!post('refresh_relation')) {
                $r = array_merge($r, $controller->listRefresh());
            }
        }

        if (post('refresh_relation')) {
            $relations = explode(',', post('refresh_relation'));
            foreach ($relations as $relation) {
                $r = array_merge($r, $controller->relationRefresh($relation));
            }
        }

        return $r;
    }

    protected function _runControllerMethod()
    {
        $url = (post('refresh_url')) ? post('refresh_url') : request()->headers->get('referer');
        // remove domain
        $pathParts = explode('/', str_replace(Request::root() . '/', '', $url));
        // Drop off preceding backend URL part if needed
        if (!empty($prefix = Config::get('cms.backendUri', 'backend')) && $prefix == $pathParts[0]) {
            array_shift($pathParts);
        }
        $path = implode('/', $pathParts);

        // 是从 _update_form 传过来的,一定是字符串
        $refreshRelationData = post('refresh_relation_data');
        if ($refreshRelationData && !is_array($refreshRelationData)) {
            $refreshRelationData = json_decode($refreshRelationData, true);
        }
        // 如果有 refresh_relation_data, 则将其合并到 request 中, 便于 controller 有自己的请求上下文
        if ($refreshRelationData) {
            request()->merge($refreshRelationData);
        }

        $requestController = $this->_getRequestedController($path);

        if (!empty($requestController)) {
            $controller = $requestController['controller'];
            $action = $requestController['action'];
            $params = $requestController['params'];
            if (in_array($action, ['create', 'update', 'preview', 'index'])) {
                $reflection = new \ReflectionClass($controller);
                $property = $reflection->getProperty('action');
                $property->setAccessible(true);
                $property->setValue($controller, $action);

                $requiredPermissionsProperty = $reflection->getProperty('requiredPermissions');
                $requiredPermissionsProperty->setAccessible(true);
                $requiredPermissions = $requiredPermissionsProperty->getValue($controller);

                if ($requiredPermissions && !$this->user->hasPermission($requiredPermissions)) {
                    throw new ApplicationException(sprintf('Backend user does not have the required permissions to access %s.', $path));
                }
                $controller->{$action}(...$params);
                return $controller;
            }
        }
        return null;
    }

    protected function _getRequestedController($url)
    {
        $params = RouterHelper::segmentizeUrl($url);

        /*
         * Look for a Plugin controller
         */
        if (count($params) >= 2) {
            list($author, $plugin) = $params;
            $pluginCode = ucfirst($author) . '.' . ucfirst($plugin);
            if (PluginManager::instance()->isDisabled($pluginCode)) {
                throw new ApplicationException(sprintf('Plugin %s is disabled.', $pluginCode));
            }

            $controller = $params[2] ?? 'index';
            $action = $action = isset($params[3]) ? $this->_parseAction($params[3]) : 'index';
            $params = $controllerParams = array_slice($params, 4);
            $controllerClass = '\\' . $author . '\\' . $plugin . '\Controllers\\' . $controller;
            if ($controllerObj = $this->_findController(
                $controllerClass,
                $action,
                plugins_path()
            )) {
                return [
                    'controller' => $controllerObj,
                    'action' => $action,
                    'params' => $controllerParams
                ];
            }
        }

        return null;
    }

    protected function _findController($controller, $action, $inPath)
    {
        /*
         * Workaround: Composer does not support case insensitivity.
         */
        if (!class_exists($controller)) {
            $controller = Str::normalizeClassName($controller);
            $controllerFile = $inPath . strtolower(str_replace('\\', '/', $controller)) . '.php';
            if ($controllerFile = File::existsInsensitive($controllerFile)) {
                include_once $controllerFile;
            }
        }

        if (!class_exists($controller)) {
            return null;
        }


        $controllerObj = App::make($controller);

        if ($controllerObj->actionExists($action)) {
            return $controllerObj;
        }

        return null;
    }


    /**
     * Process the action name, since dashes are not supported in PHP methods.
     * @param  string $actionName
     * @return string
     */
    protected function _parseAction($actionName)
    {
        if (strpos($actionName, '-') !== false) {
            return camel_case($actionName);
        }

        return $actionName;
    }
}
