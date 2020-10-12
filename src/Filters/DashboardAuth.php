<?php namespace CI4Xpander_Dashboard\Filters;

class DashboardAuth extends \CI4Xpander\Filters\Auth
{
    use \CodeIgniter\API\ResponseTrait;

    public function before(\CodeIgniter\HTTP\RequestInterface $request, $params = null)
    {
        if (in_array('web', $params)) {
            if (in_array('outside', $params)) {
                if ($this->session->has('user')) {
                    return redirect('dashboard');
                } else {
                    \Config\Services::modelTracker()->setCreatedBy(0);
                    \Config\Services::modelTracker()->setUpdatedBy(0);
                    \Config\Services::modelTracker()->setDeletedBy(0);
                }
            } elseif (in_array('inside', $params)) {
                if (!$this->session->has('user')) {
                    $this->session->destroy();
                    \Config\Services::modelTracker()->setCreatedBy(0);
                    \Config\Services::modelTracker()->setUpdatedBy(0);
                    \Config\Services::modelTracker()->setDeletedBy(0);
                    return redirect('login');
                } else {
                    $user = $this->session->user;
                    \Config\Services::modelTracker()->setCreatedBy($user->id);
                    \Config\Services::modelTracker()->setUpdatedBy($user->id);
                    \Config\Services::modelTracker()->setDeletedBy($user->id);
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
                    return \Config\Services::response()->failUnauthorized();
                }
            } elseif (in_array('inside', $params)) {
                if (!$this->session->has('user')) {
                    $this->session->destroy();
                    \Config\Services::modelTracker()->setCreatedBy(0);
                    \Config\Services::modelTracker()->setUpdatedBy(0);
                    \Config\Services::modelTracker()->setDeletedBy(0);
                    \Config\Services::response()->failUnauthorized();
                } else {
                    $user = $this->session->user;
                    \Config\Services::modelTracker()->setCreatedBy($user->id);
                    \Config\Services::modelTracker()->setUpdatedBy($user->id);
                    \Config\Services::modelTracker()->setDeletedBy($user->id);
                }
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
