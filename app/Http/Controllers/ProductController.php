<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prestashop;
use Protechstudio\PrestashopWebService\PrestashopWebService AS PrestashopService;

class ProductController extends Controller
{

    function __construct($foo = null)
    {
        $this->perPage = 10;
        $this->limit = '10';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $opt['resource'] = 'products';
        $xml = Prestashop::get($opt);
        $json = json_encode($xml->products);
        $array = json_decode($json, true);
        $count = count($array['product']);
        $page = 0;
        if (isset($_GET['page'])) {
            if (!is_numeric($_GET['page']) || $_GET['page'] > $count) {
                return redirect()->route('product.index');
            }
            $page = $_GET['page'];
            $this->limit = $_GET['page'].",".$this->limit;
        }
        $opt['display'] = '[name,id]';
        $opt['sort']  = 'id_DESC';
        $opt['limit']  = $this->limit;
        $xml = Prestashop::get($opt);
        $resources = $xml->children()->children();
        $limit = $this->perPage;

        return view(
            'product.products', 
            compact(
                'resources', 
                'count', 
                'page', 
                'limit'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return HTML
     */
    public function create()
    {
        $opt = array('resource' => 'products');
        $xml = Prestashop::get(
            [
                'url' => config('prestashop-webservice.url').
                '/api/products?schema=blank'
            ]
        );
        $resources = $xml->children()->children();
        unset($resources->id);
        unset($resources->position_in_category);
        unset($resources->id_shop_default);
        unset($resources->date_add);
        unset($resources->date_upd);

        unset($resources->associations->combinations);
        unset($resources->associations->product_options_values);
        unset($resources->associations->product_features);
        unset(
            $resources->associations->stock_availables
                ->stock_available->id_product_attribute
        );
        return view('product.create', compact('resources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Store Request Object
     * 
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'json_file' => 'required|mimes:json'
            ]
        );
        $file_path = public_path() . '/jsonfiles/' . $request->file('json_file')->getClientOriginalName();
        $file = $request->file('json_file')->move(public_path() . '/jsonfiles', $request->file('json_file')->getClientOriginalName());
        $content = \File::get($file_path);
        $array = json_decode($content);
        if ($array) {
            foreach ($array as $key => $value) {
                $xml = Prestashop::get(
                    [
                        'url' => config('prestashop-webservice.url').
                        '/api/products?schema=blank'
                    ]
                );
                $resources = $xml->children()->children();

                unset($resources->id);
                unset($resources->position_in_category);
                unset($resources->id_shop_default);
                unset($resources->date_add);
                unset($resources->date_upd);

                unset($resources->associations->combinations);
                unset($resources->associations->product_options_values);
                unset($resources->associations->product_features);
                unset(
                    $resources->associations->stock_availables
                        ->stock_available->id_product_attribute
                );
                $resources->id_manufacturer = '1';
                $resources->id_supplier = '1';
                $resources->id_category_default = 4;
                $resources->new = '0';
                ; //condition, new is also a php keyword!!
                $resources->id_default_combination = '0';
                $resources->id_tax_rules_group = 3;
                $resources->reference = 'demo_1';
                // $resources->minimal_quantity = $_POST['minimal_quantity'];
                $resources->price = $value->price->store;
                $resources->active = 1;
                $resources->available_for_order = 1;
                $resources->show_price = 1;
                $resources->indexed = '1';
                $resources->visibility = 'both';
                $resources->advanced_stock_management='0';
                $resources->pack_stock_type = 3;
                $resources->state = 1;

                $resources->associations->categories
                    ->addChild('category')->addChild('id', $value->categories->category_id);

                $node = dom_import_simplexml($resources->name->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->name));
                $resources->name->language[0][0] = $value->texts->name;
                $resources->name->language[0][0]['id'] = 1;
                $resources->name->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->description->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->description));
                $resources->description->language[0][0] = $value->texts->description;
                $resources->description->language[0][0]['id'] = 1;
                $resources->description->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->description_short->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->title));
                $resources->description_short->language[0][0] = $value->texts->title;
                $resources->description_short->language[0][0]['id'] = 1;
                $resources->description_short->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->link_rewrite->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection('test'));
                $resources->link_rewrite->language[0][0] = 'test';
                $resources->link_rewrite->language[0][0]['id'] = 1;
                $resources->link_rewrite->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->meta_title->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->title));
                $resources->meta_title->language[0][0] = $value->texts->title;
                $resources->meta_title->language[0][0]['id'] = 1;
                $resources->meta_title->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->meta_description->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->meta_description));
                $resources->meta_description->language[0][0] = $value->texts->meta_description;
                $resources->meta_description->language[0][0]['id'] = 1;
                $resources->meta_description->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->meta_keywords->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value->texts->meta_keywords));
                $resources->meta_keywords->language[0][0] = $value->texts->meta_keywords;
                $resources->meta_keywords->language[0][0]['id'] = 1;
                $resources->meta_keywords->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->available_now->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection('test'));
                $resources->available_now->language[0][0] = 'test';
                $resources->available_now->language[0][0]['id'] = 1;
                $resources->available_now->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                $node = dom_import_simplexml($resources->available_later->language[0][0]);
                $no = $node->ownerDocument;
                $node->appendChild($no->createCDATASection('test'));
                $resources->available_later->language[0][0] = 'test';
                $resources->available_later->language[0][0]['id'] = 1;
                $resources->available_later->language[0][0]['xlink:href'] 
                    = config('prestashop-webservice.url') . 
                    '/api/languages/' . 1;

                /*foreach ($value->texts->bulletpoints as $tag){
                    $resources->associations->tags->addChild('tags')->addChild('id', $tag);
                }*/

                $opt = array('resource' => 'products');
                $opt['postXml'] = $xml->asXML();
                $xml = Prestashop::add($opt);
                $id = $xml->product->id;
                if($value->images) {
                    foreach ($value->images as $image) {
                        $this->uploadImage($id, $image);
                    }
                }
            }
        }


        return redirect()
            ->route('product.index')
            ->with('success', 'Product Added Successfully!');
    }

    public function createCategory()
    {
        $xmlSchema=Prestashop::getSchema('categories'); //returns a SimpleXMLElement instance with the desired schema

        $data=[
            'id_parent' => 9,
            'name'=>'Piano',
            'link_rewrite'=>'Piano',
            'active'=>true,
        ];
        $postXml=Prestashop::fillSchema($xmlSchema,$data); 

        //The xml is now ready for being sent back to the web service to create a new category

        $response=Prestashop::add(['resource'=>'categories','postXml'=>$postXml->asXml()]);
    }

    public function uploadImage($productId='',$image_path='')
    {
        $urlImage = config('prestashop-webservice.url').'/api/images/products/'.$productId.'/';
        $key  = config('prestashop-webservice.token');
        //Here you set the path to the image you need to upload
        $image_mime = $this->get_mime_type( $image_path );

        $args['image'] = new \CurlFile($image_path, $image_mime);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_URL, $urlImage);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $key.':');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 == $httpCode) {
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Product ID
     *
     * @return HTML
     */
    public function show($id)
    {
        $opt['resource'] = 'products';
        $opt['id'] = $id;
        $xml = Prestashop::get($opt);
        $resources = $xml->children()->children();
        $product = $this->xml2array($resources);
        $tax_rules_group = [
            'No tax',
            'IN Reduced Rate (4%)',
            'IN Standard Rate (12.5%)',
            'IN Super Reduced Rate (1%)'
        ];
        return view('product.product', compact('product', 'tax_rules_group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id Product ID
     *
     * @return HTML
     */
    public function edit($id)
    {
        $opt['resource'] = 'products';
        $opt['id'] = $id;
        $xml = Prestashop::get($opt);
        $resources = $xml->children()->children();
        $product = $this->xml2array($resources);
        return view('product.edit', compact('resources', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Update Request
     * @param int                      $id      Product ID
     * 
     * @return void
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'price' => array('required','regex:/^\d{1,13}(\.\d{1,6})?$/'),
                'reference' => 'required'
            ]
        );
        // call to retrieve customer with ID 2
        $xml = Prestashop::get(
            [
                'resource' => 'products',
                'id' => $id, // Here we use hard coded value 
                // but of course you could get this ID from a 
                // request parameter or anywhere else
            ]
        );
        $resources = $xml->product->children();
        unset($resources->manufacturer_name);
        unset($resources->quantity);

        $postKeys = array_keys($_POST);
        // Here we have XML before update, lets update XML with new values
        foreach ($resources as $nodeKey => $node) {
            if (in_array($nodeKey, $postKeys)) {
                $resources->$nodeKey = $_POST[$nodeKey];
            }
        }

        try {
            $opt = array('resource' => 'products');
            $opt['putXml'] = $xml->asXML();
            $opt['id'] = (int) $resources->id;
            $xml = Prestashop::edit($opt);
            return redirect()
                ->route('product.index')
                ->with('success', 'Product Updated Successfully!');
        } catch (Exception $e) {
            // Here we are dealing with errors
            $trace = $ex->getTrace();
            if ($trace[0]['args'][0] == 404) {
                echo 'Bad ID';
            } elseif ($trace[0]['args'][0] == 401) {
                echo 'Bad auth key';
            } else {
                echo 'Other error<br />'.$ex->getMessage();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Product ID
     * 
     * @return void
     */
    public function destroy($id)
    {
        Prestashop::delete(
            [
                'resource' => 'products',
                'id' => $id, // Here we use hard coded value but of 
                // course you could get this ID from a request 
                // parameter or anywhere else
            ]
        );
        return redirect()
            ->route('product.index')
            ->with('success', 'Product Deleted Successfully!');
    }

    /**
     * Fetch the products by id and convert XML into array.
     *
     * @param int $id Product ID
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch($id = '')
    {
        $opt['resource'] = 'products';
        $opt['id'] = $id;
        $xml = Prestashop::get($opt);
        $resources = $xml->children()->children();
        return $this->xml2array($resources);
    }

    /**
     * Take XML Object and convert it into array by recursion.
     *
     * @param object $xmlObject XML Object
     * @param array  $out       Array of values
     *
     * @return array
     */
    public function xml2array($xmlObject, $out = array ())
    {
        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = ( is_object($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }

    public function get_mime_type($filename) {
    $idx = explode( '.', $filename );
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode-1]);

    $mimet = array( 
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    if (isset( $mimet[$idx] )) {
     return $mimet[$idx];
    } else {
     return 'application/octet-stream';
    }
 }
}
