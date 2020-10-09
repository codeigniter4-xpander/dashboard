<?php namespace CI4Xpander_Dashboard\Filters;

class DashboardAuth extends \CI4Xpander\Filters\Auth
{
    use \CodeIgniter\API\ResponseTrait;

    public $reponse;

    public function __construct()
    {
        $this->response = \Config\Services::response();
    }

    public function before(\CodeIgniter\HTTP\RequestInterface $request, $params = null)
    {
        if (in_array('web', $params)) {
            if (in_array('outside', $params)) {
                if ($this->session->has('user')) {
                    return redirect('dashboard');
                }
            } elseif (in_array('inside', $params)) {
                if (!$this->session->has('user')) {
                    $this->session->destroy();
                    \Config\Services::modelTracker()->setCreatedBy(0);
                    \Config\Services::modelTracker()->setUpdatedBy(0);
                    \Config\Services::modelTracker()->setDeletedBy(0);
                    return redirect('login');
                }
            } else {
                $this->session->destroy();
                \Config\Services::modelTracker()->setCreatedBy(0);
                \Config\Services::modelTracker()->setUpdatedBy(0);
                \Config\Services::modelTracker()->setDeletedBy(0);
                return redirect('login');
            }
        } elseif (in_array('ajax', $params)) {
            if (in_array('outside', $params)) {
                if ($this->session->has('user')) {
                    return $this->failUnauthorized();
                }
            } elseif (in_array('inside', $params)) {
                if (!$this->session->has('user')) {
                    $this->session->destroy();
                    \Config\Services::modelTracker()->setCreatedBy(0);
                    \Config\Services::modelTracker()->setUpdatedBy(0);
                    \Config\Services::modelTracker()->setDeletedBy(0);
                    $this->failUnauthorized();
                }
            } else {
                $this->session->destroy();
                \Config\Services::modelTracker()->setCreatedBy(0);
                \Config\Services::modelTracker()->setUpdatedBy(0);
                \Config\Services::modelTracker()->setDeletedBy(0);
                $this->failUnauthorized();
            }
        } else {
            $this->session->destroy();
            \Config\Services::modelTracker()->setCreatedBy(0);
            \Config\Services::modelTracker()->setUpdatedBy(0);
            \Config\Services::modelTracker()->setDeletedBy(0);
            return redirect('login');
        }
    }
}
