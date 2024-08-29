<div class="filter-holder">
          <div class="filter-keep">
                <form method="GET" action  = "{{route('training.search')}}">
                  @csrf
                  <div class="filter-box">
                        <div class="form-group input-key">
                          <label for="exampleFormControlSelect1 label-key">STATUS</label>
                          <select class="form-control select-key" name ="status">
                            <option value="">- SELECT -</option>
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="overdue">Overdue</option>
                          </select>
                        </div>
                        <div class="form-group input-key">
                          <label for="exampleFormControlSelect1 label-key">MODE</label>
                          <select class="form-control select-key" name ="mode">
                            <option value = "" >- SELECT -</option>
                            <option value="mandatory">Mandatory</option>
                            <option value="optional">Optional</option>
                          </select>
                        </div>
                        <div class="form-group input-key">
                          <label for="exampleFormControlSelect1 label-key">TYPE</label>
                          <select class="form-control select-key" name ="type">
                            <option value="">- SELECT -</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="hybrid">Hybrid</option>
                          </select>
                        </div>
                        <div class="form-group input-key">
                          <label for="exampleFormControlSelect1 label-key">FROM</label>
                          <input class="form-control" type="date" name ="from">
                          
                        </div>
                        <div class="form-group input-key">
                          <label for="exampleFormControlSelect1 label-key">T0</label>
                          <input class="form-control" type="date" name ="to">
                        </div>
                  </div>
                  <div class="btn-key">
                    <button type = "submit" class="btn btn-block btn-primary search-btn"><i class="icon fa fa-search"
                      aria-hidden="true"></i>&nbsp;search</button>
                  </div>
                </form>
          </div>
        </div>