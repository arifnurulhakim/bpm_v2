<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Orderan;

use App\Models\Harga;

use App\Models\Customer;

use App\Models\Penerima;

use League\Csv\Writer;

use Dompdf\Dompdf;

use Dompdf\Options;

class OrderanController extends Controller

{

    public function index()

    {

        $customer = Harga::select('nama_customer')->distinct()->orderby('nama_customer','asc')->get();


        $penerima = Harga::select('nama_penerima')->distinct()->orderby('nama_penerima','asc')->get();

        return view('orderan.index',compact('customer','penerima'));

    }


    public function data()
    {
        $orderan = Orderan::orderBy('created_at', 'desc')->get();
        
        return datatables()
            ->of($orderan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($orderan) {
                $disabled = '';
                if(auth()->user()->level == 2 || auth()->user()->level == 3){
                    if ($orderan->status > 1) {
                        $disabled = 'disabled';
                    }
                }else{
                    $disabled = 'enabled';
                }

                return '
                    <div class="btn-group">
                        <button type="button" onclick="showDetail(`'. route('orderan.show', $orderan->id_orderan) .'`)" class="btn btn-xs btn-primary btn-flat" ><i class="fa fa-eye"></i></button>
                        <button type="button" onclick="editForm(`'. route('orderan.update', $orderan->id_orderan) .'`)" class="btn btn-xs btn-info btn-flat" '. $disabled .'><i class="fa fa-pencil"></i></button>
                        <button type="button" onclick="deleteData(`'. route('orderan.destroy', $orderan->id_orderan) .'`)" class="btn btn-xs btn-danger btn-flat" '. $disabled .'><i class="fa fa-trash"></i></button>
                        <button type="button" onclick="exportPDF(`'. route('orderan.exportPDF', $orderan->id_orderan) .'`)" class="btn btn-xs btn-success btn-flat" ><i class="fa fa-book"></i></button>
                      
                    </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    
    public function show($id)
    {
        $order = Orderan::findOrFail($id);
          return response()->json($order);
    }
    
    



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        $get_nama_customer = $request->nama_customer;

        $get_nama_penerima = $request->nama_penerima;

        

        $get_customer = Customer::where('nama_customer', $get_nama_customer)->first();

        $get_penerima = Penerima::where('nama_penerima', $get_nama_penerima)->first();

        // dd($get_customer);



        // $get_alamat_customer = $get_customer->alamat_customer;

        // $get_alamat_penerima = $get_penerima->alamat_penerima;

        

        $get_harga = Harga::where('nama_customer', $get_customer->nama_customer)

                        ->where('nama_penerima', $get_penerima->nama_penerima)

                        ->first();

                        // dd($get_harga);

        $jenis_harga = '';

        // $berat_barang=0;

        if($request->jenis_harga == 'roll'){

            $jenis_harga = $get_harga->harga_roll;

        }else if($request->jenis_harga == 'ball'){

            $jenis_harga = $get_harga->harga_ball;

        }else if($request->jenis_harga == 'tonase'){

            $jenis_harga = $get_harga->harga_tonase;

        }

       

        // if($request->berat_barang == null){

        //     $berat_barang = 1;

        // }else{

        //     $berat_barang = $request->berat_barang;

        // }

        // $harga = ($request->berat_barang * $jenis_harga) * $request->jumlah_barang;

   

        $orderan = new Orderan();

        $orderan->kode_tanda_penerima = $request->kode_tanda_penerima;

        $orderan->id_harga = $get_harga->id_harga;

        $orderan->nama_customer = $get_customer->nama_customer;

        $orderan->alamat_customer = $get_customer->alamat_customer;

        $orderan->telepon_customer = $get_customer->telepon_customer;



        $orderan->nama_barang = $request->nama_barang;

        $orderan->jumlah_barang = $request->jumlah_barang;
        
        $orderan->sisa_jumlah_barang = $request->jumlah_barang;

        // $orderan->berat_barang = $request->berat_barang;
        
        $orderan->jenis_berat = $request->jenis_harga;



        $orderan->nama_penerima = $get_penerima->nama_penerima;

        $orderan->alamat_penerima = $get_penerima->alamat_penerima;

        $orderan->telepon_penerima = $get_penerima->telepon_penerima;



        $orderan->supir = $request->supir;

        $orderan->no_mobil = $request->no_mobil;

        $orderan->keterangan = $request->keterangan;

        $orderan->tanggal_pengambilan = $request->tanggal_pengambilan;

        // $orderan->harga = $harga;

        $orderan->status = 1;

        $orderan->tagihan_by = $request->tagihan_by;


        $orderan->save();



        return response()->json('Data berhasil disimpan', 200);

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */


    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        $orderan = Orderan::find($id);

        $orderan->kode_tanda_penerima = $request->kode_tanda_penerima;

        $orderan->nama_customer = $request->nama_customer;

       

        $orderan->nama_barang = $request->nama_barang;

        $orderan->jumlah_barang = $request->jumlah_barang;

        // $orderan->berat_barang = $request->berat_barang;
        $orderan->jenis_berat = $request->jenis_harga;

        $orderan->nama_penerima = $request->nama_penerima;

      

        $orderan->supir = $request->supir;

        $orderan->no_mobil = $request->no_mobil;

        $orderan->keterangan = $request->keterangan;

        $orderan->tanggal_pengambilan = $request->tanggal_pengambilan;

        $orderan->tagihan_by = $request->tagihan_by;

        $orderan->update();



        return response()->json('Data berhasil disimpan', 200);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $orderan = Orderan::find($id)->delete();



        return response(null, 204);

    }



    public function deleteSelected(Request $request)

    {

        foreach ($request->id_orderan as $id) {

            $orderan = Orderan::find($id);

            $orderan->delete();

        }



        return response(null, 204);

    }



    public function exportCSV()

    {

        $orderan = Orderan::get();

     

// Generate a timestamp for the filename

$timestamp = date('YmdHis');



// Set the headers to download the file with the timestamp in the filename

header('Content-Type: text/csv');

header('Content-Disposition: attachment; filename="orderan_'.$timestamp.'.csv"');

    

        // Create a new instance of the League CSV Writer

        $csv = Writer::createFromString('');

    

        // Add the header row

        $csv->insertOne([

            'ID Orderan',

            'Kode Tanda Penerima',

            'Nama Customer',

            'Alamat Customer',

            'Telepon Customer',

            'Nama Barang',

            'Jumlah Barang',

            'Berat Barang',

            'Nama Penerima',

            'Alamat Penerima',

            'Telepon Penerima',

            'Supir',

            'No. Mobil',

            'Keterangan',

            'Tanggal Pengambilan',

            'Tanggal Kirim',

            'Tanggal Terima',

            'Harga',

            'Created At',

            'Updated At'

        ]);

    

        // Add the data rows

        foreach ($orderan as $order) {

            $csv->insertOne([

                $order->id_orderan,

                $order->kode_tanda_penerima,

                $order->nama_customer,

                $order->alamat_customer,

                $order->telepon_customer,

                $order->nama_barang,

                $order->jumlah_barang,

                $order->berat_barang,

                $order->nama_penerima,

                $order->alamat_penerima,

                $order->telepon_penerima,

                $order->supir,

                $order->no_mobil,

                $order->keterangan,

                $order->tanggal_pengambilan,

                $order->tanggal_kirim,

                $order->tanggal_terima,

                $order->harga,

                $order->created_at,

                $order->updated_at

            ]);

        }

    

        // Return the CSV file as a response

        return response($csv->__toString());

    }



    public function exportPDF($id)

{

    $orderan = Orderan::find($id);



    // Membuat file PDF

    $options = new Options();

    $options->setIsRemoteEnabled(true);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml(view('orderan.pdf', compact('orderan'))->render());

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();



    // Menghasilkan file PDF dan mengirimkan ke browser

    return $dompdf->stream('orderan'.$orderan->kode_tanda_penerima . $orderan->created_at . '.pdf');

}
public function getPenerimaByCustomer(Request $request)
    {
        $customer = $request->input('customer');

        // Mengambil data penerima berdasarkan customer dari tabel harga
        $penerima = Penerima::where('customer_id', $customer)->get();

        return response()->json(['penerima' => $penerima]);
    }

    



}

