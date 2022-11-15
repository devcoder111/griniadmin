       
         <input type="hidden" name="id" value="{{ $data->id }}">
         <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <div class="form-group" id="note_field" style="display: none;">
                <div class="col-sm-12">
                    <label>Notes</label>
                    <textarea class="form-control form-control-sm" name="note" placeholder="Enter Note Here" style="margin-bottom: 20px;">{{$data->note}}</textarea>
                    
                </div>
            </div>
            <div class="form-group" id="color_field"  style="display: none;">
                <div class="col-sm-12">
                   
                      <select name="note_color"  class="form-control">
                        <option value="">Select Color</option>
                       
                        <option value="#ffffff" {{ ($data->note_color == "#ffffff")  ? 'selected' : ''}}>White</option>
                        <option value="#CD5C5C" {{ ($data->note_color == "#CD5C5C")  ? 'selected' : ''}}>Red</option>
                        <option value="#7FFF00" {{ ($data->note_color == "#7FFF00")  ? 'selected' : ''}}>Green</option>
                        <option value="#FFFF00" {{ ($data->note_color == "#FFFF00")  ? 'selected' : ''}}>Yellow</option>
                        <option value="#00FFFF"  {{ ($data->note_color == "#00FFFF")  ? 'selected' : ''}}>Cyan</option>
                        <option value="#F4A460" {{ ($data->note_color == "#F4A460")  ? 'selected' : ''}}>Brown</option>
                        <option value="#B0C4DE" {{ ($data->note_color == "#B0C4DE")  ? 'selected' : ''}}>Blue</option>
                        <option value="#9370DB"  {{ ($data->note_color == "#9370DB")  ? 'selected' : ''}}>Purple</option>


                      </select>
               
                    
                </div>
            </div>

            <div class="form-group" id="order_status_field" style="display: none;">
                <div class="col-sm-12">
                     <label>Item Status</label>
                    <select name="item_status"  class="form-control">
                         <option value="fulfilled" {{ ($data->item_status == "fulfilled")  ? 'selected' : ''}}  >fulfilled</option>
                        
                        <option value="unfulfilled" {{ ($data->item_status == "unfulfilled" || $data->item_status == "Unfulfilled")  ? 'selected' : ''}} >Unfulfilled</option>
                        
                        
                    </select>
                   
                </div>
            </div>
