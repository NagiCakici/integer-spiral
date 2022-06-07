<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *    title="integer spiral",
 *    version="1.0.0",
 * )
 */

class projectsApiController extends Controller
{
    private $spiral = [];
    private $gi = 0;
    private $ti = 0;

    /**
     * @OA\Get(
     *    path="/api/x/y",
     *    operationId="spiral",
     *    tags={"integer-spiral"},
     *    description="It creates an integer spiral from the outside to the inside with the entered X and Y values and outputs as Json. Example: Request URL = /api/10/8 for X=10, Y=8 (Girilen X ve Y değerleri ile dıştan içe doğru integer spiral oluşturur ve Json olarak çıktı verir. Örnek: X=10, Y=8 için istek URL = /api/10/8)",
     *    summary="URL Format (URL Yapısı)",
     *      @OA\Parameter(
     *         name="X",
     *         in="query",
     *         description="X Coordinate",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="Y",
     *         in="query",
     *         description="Y Coordinate",
     *         required=true,
     *      ),
     * 
     * 
     * 
     *    @OA\Response(
     *        response=200,
     *        description="When X and Y coordinates are entered as 5 and 4 respectively, the following result is obtained. (X ve Y koordinatları sırasıyla 5 ve 4 girildiğinde aşağıdaki sonuç alınır.)",
     *        @OA\JsonContent(
     *            @OA\Property(property="0", type="json", example="{'0':0,'1':1,'2':2,'3':3,'4':4}"),
     *            @OA\Property(property="1", type="json", example="{'4':5,'0':13,'1':14,'2':15,'3':16}"),
     *            @OA\Property(property="2", type="json", example="{'4':6,'0':12,'3':17,'2':18,'1':19}"),
     *            @OA\Property(property="3", type="json", example="{'4':7,'3':8,'2':9,'1':10,'0':11}"),
     *          )
     *    ),
     * 
     *    @OA\Response(
     *        response=401,
     *        description="X and Y values are mandatory and must be integer.(X ve Y değerleri zorunludur ve integer olmalıdır.)",
     *        @OA\JsonContent(
     *            @OA\Property(property="error_code", type="json", example="401"),
     *            @OA\Property(property="error_mesage", type="json", example="X and Y values are mandatory and must be integer.")
     *          )
     *    ),
     * 
     *    @OA\Response(
     *        response=402,
     *        description="Calculation error (Hesaplama Hatası)",
     *        @OA\JsonContent(
     *            @OA\Property(property="error_code", type="json", example="402"),
     *            @OA\Property(property="error_mesage", type="json", example="Calculation error.")
     *          )
     *    ),
     * 
     *    @OA\Response(
     *        response=403,
     *        description="X and Y values can be a maximum of 2000 (X ve Y değerleri maksimum 2000 olabilir)",
     *        @OA\JsonContent(
     *            @OA\Property(property="error_code", type="json", example="403"),
     *            @OA\Property(property="error_mesage", type="json", example="X and Y values can be a maximum of 2000.")
     *          )
     *    )
     * )
     */
    public function index()
    {
        return redirect('/api/documentation');
    }

    public function spiral()
    {
        
        $kx = intval(request()->x);
        $ky = intval(request()->y);
        $this->ti = $kx * $ky;
        if ($kx && $ky) {
            if ($kx <= 2000 && $ky <= 2000) {
                $this->xyon(0, 0, $kx+1, $ky);

            } else {
                $this->errx(403);
            }
        } else {
            $this->errx(401);
        }
    }


    public function xyon($y, $x, $kx, $ky){
        $kx--;
        if($kx <= 0){$this->errx(402);}
        for($i = 0; $i < $kx; $i++){
            $this->spiral[$y][$x] = $this->gi;
            $this->gi++;
            $x++;
        }
        if($this->gi < $this->ti){
            $this->yyon($y+1, $x-1, $kx, $ky);
        }else{
            $this->rtrn();
        }

    }


    public function yyon($y, $x, $kx, $ky){
        $ky--;
        if($ky <= 0){$this->errx(402);}
        for($i = 0; $i < $ky; $i++){
            $this->spiral[$y][$x] = $this->gi;
            $this->gi++;
            $y++;
        }
        if($this->gi < $this->ti){
            $this->xson($y-1, $x-1, $kx, $ky);
        }else{
            $this->rtrn();
        }
    }


    public function xson($y, $x, $kx, $ky){
        $kx--;
        if($kx <= 0){$this->errx(402);}
        for($i = 0; $i < $kx; $i++){
            $this->spiral[$y][$x] = $this->gi;
            $this->gi++;
            $x--;
        }
        if($this->gi < $this->ti){
            $this->yson($y-1, $x+1, $kx, $ky);
        }else{
            $this->rtrn();
        }
    }


    public function yson($y, $x, $kx, $ky){
        $ky--;
        if($ky <= 0){$this->errx(402);}
        for($i = 0; $i < $ky; $i++){
            $this->spiral[$y][$x] = $this->gi;
            $this->gi++;
            $y--;
        }
        if($this->gi < $this->ti){
            $this->xyon($y+1, $x+1, $kx, $ky);
        }else{
            $this->rtrn();
        }
    }

    public function rtrn(){
        $rtrn = json_encode($this->spiral, JSON_FORCE_OBJECT);
        die($rtrn);
    }
    public function errx($err){
        $error_mesage = [
            '401' => 'X and Y values are mandatory and must be integer.',
            '402' => 'Calculation error.',
            '403' => 'X and Y values can be a maximum of 2000.',
        ];
        $rtrn = json_encode(['error_code' => $err, 'error_mesage' => $error_mesage[$err]], true);
        die($rtrn);
    }
}
