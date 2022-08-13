@if($schedule->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-secondary"style="color:white;">
                            <tr>
                                <th>Specialization</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                      

                            @foreach($schedule as $calendar)

                            <tr>
                                <td>{{$calendar->specialization}}</td>
                                <td>{{$calendar->day}}</td>
                                <td>{{$calendar->time}}</td>
                                <td>
                                    <form action="{{route('health-center-deleteschedule',$calendar->id)}}"method="POST">
                                        @csrf 
                                    
                                        {{method_field('DELETE')}}

                                        <button class="btn"style="background:none;color:red;font-weight:bold;">Remove</button>
                                
                                    </form>
                                </td>
                            </tr>

                            @endforeach
                            
                            @else <div class="text-center py-2">
                                <h5 class="text-center">Not Available</h5>
                            </div>

                           @endif
                        </tbody>
                    </table>
                </div>