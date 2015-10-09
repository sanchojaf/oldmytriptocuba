<?php
namespace Payum\Paypal\ExpressCheckout\Nvp\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Paypal\ExpressCheckout\Nvp\Request\Api\GetTransactionDetailsRequest;
use Payum\Core\Exception\RequestNotSupportedException;

class GetTransactionDetailsAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /** @var $request GetTransactionDetailsRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }
        
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $transactionIndex = 'PAYMENTREQUEST_'.$request->getPaymentRequestN().'_TRANSACTIONID';
        if (false == $model[$transactionIndex]) {
            throw new LogicException($transactionIndex.' must be set.');
        }

        $result = $this->api->getTransactionDetails(array('TRANSACTIONID' => $model[$transactionIndex]));
        foreach ($result as $name => $value) {
            if (in_array($name, $this->getPaymentRequestNFields())) {
                $model['PAYMENTREQUEST_'.$request->getPaymentRequestN().'_'.$name] = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return 
            $request instanceof GetTransactionDetailsRequest &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    /**
     * @return array
     */
    protected function getPaymentRequestNFields()
    {
        return array(
            'TRANSACTIONID',
            'PARENTTRANSACTIONID',
            'RECEIPTID',
            'TRANSACTIONTYPE',
            'PAYMENTTYPE',
            'ORDERTIME',
            'AMT',
            'CURRENCYCODE',
            'FEEAMT',
            'SETTLEAMT',
            'TAXAMT',
            'EXCHANGERATE',
            'PAYMENTSTATUS',
            'PENDINGREASON',
            'REASONCODE'
        );
    }
}