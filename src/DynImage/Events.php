<?php

namespace DynImage;


final class Events {
    
     const AFTER_CREATE_IMAGE = 'dynimage.after.create.image';
     const FINISH_CREATE_IMAGE = 'dynimage.finish.create.image';
     
     const EARLY_APPLY_FILTER = 'dynimage.early.apply.filter';
     const LATE_APPLY_FILTER = 'dynimage.late.apply.filter';
     
}
