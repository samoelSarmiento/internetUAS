<?php

namespace Intranet\Models;

use Illuminate\Database\Eloquent\Model;

class Evquestionxstudentxdocente extends Model
{
    public function pregunta(){
  	  return $this->belongsTo('Intranet\Models\Evquestion','id_evquestion');
    }

    public function evaluador(){
  	  return $this->belongsTo('Intranet\Models\Teacher','id_docente');
    }
}
