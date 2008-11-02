<?php
class Redirector extends DSF_Db_Table 
{
    protected $_name = 'redirectors';
    
    /**
     * returns the redirect for the selected path
     *
     * @param string $path
     * @return mixed
     */
    public function get($path)
    {
        $where[] = $this->_db->quoteInto("request = ?", $path);
        return $this->fetchRow($where);
    }
    
    /**
     * if there is a redirector set for the current page this will return
     * the path and response code
     *
     * @return stdClass object
     */
    public function getCurrentRedirector()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if($newPath = $this->get($uri)){
            $response = new stdClass();
            if($newPath->response_code > 0){
                $response->responseCode = intval($newPath->response_code);
            }
            if(Zend_Uri::check($newPath->response)){
                //this is a valid http uri.  return as a string to redirect
                $response->path = $newPath->response;
                return $response;
            }else{
                //this is not a valid http uri.  return as an array to find the page
                $uriObj = new DSF_Uri();
                $response->path = $uriObj->toArray($newPath->response);
                return $response;
            }
        }
    }
    
    /**
     * adds a new redirector
     * if the redirector exists then it updates the current
     *
     * @param string $request
     * @param string $response
     * @return mixed
     */
    public function add($request, $response, $responseCode = null)
    {
        if(!empty($request) && !empty($response)){
            $data = array(
                'request'  => $request,
                'response'    => $response,
                'response_code' => $responseCode
            );
            
            if(!$this->get($request)){
                //if this row does not exist then insert it
                $this->insert($data);
            }else{
                //update the existing row
                $where[] = $this->_db->quoteInto("request = ?", $request);
                $this->update($data, $where);
            }
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * this function removes all of the current redirector
     * it then inserts one for each row
     *
     * @param array $request
     * @param array $response
     * @param array $responseCode
     */
    public function setFromArray($request, $response, $responseCode)
    {
        //remove all
        $this->delete(null);
        
        $count = count($request);
        
        for($i = 0; $i <= ($count - 1); $i++){
            $this->add($request[$i], $response[$i], $responseCode[$i]);
        }
    }
}