               <input type="hidden" name="order_id" value="{{ $data['ids'] }}">
          
	           <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
	           
	            <div class="form-group" id="order_row_color" >
	                <div class="col-sm-12">
	                   
	                      <select name="order_row_color"  class="form-control">

	                        <option value="">Select Color </option>
	                            <option value="#ffffff" >White</option>
	                            <option value="#CD5C5C">Red</option>
	                            <option value="#7FFF00">Green</option>
	                            <option value="#FFFF00">Yellow</option>
	                            <option value="#00FFFF">Cyan</option>
	                            <option value="#F4A460">Brown</option>
	                            <option value="#B0C4DE">Blue</option>
	                            <option value="#9370DB">Purple</option>
	                 
	                      </select>
	               </div>
	          </div>