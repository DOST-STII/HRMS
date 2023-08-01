<!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box" style="cursor: pointer" onclick='window.location.href = "{{ url('dashboard-staff/ALL') }}"'>
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Employee</span>
                <span class="info-box-number">
                  {{ infoBoxEmployee() }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" style="cursor: pointer" onclick='window.location.href = "{{ url('dashboard-employee/ALL') }}"'>
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Plantilla Position</span>
                <span class="info-box-number"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" style="cursor: pointer" onclick='window.location.href = "{{ url('retiree/ALL') }}"'>
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-heart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Retiree</span>
                <span class="info-box-number">{{ infoBoxRetiree() }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <!-- <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Lates</span>
                <span class="info-box-number">40</span>
              </div>
            </div> -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->