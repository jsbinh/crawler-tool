<div align="center">
    <h1>Crawler tools</h1>
    <p>
        <!-- Progress: <span class="count"></span> / <span class="total_count"></span> -->
    </p>
    <?php echo $this->Form->create('Pages',  array('type'=>'file', 'class'=>'form-group')); ?>
        <table class="">
            <tbody>
                <tr>
                    <td>
                        Input CSV:
                    </td>
                    <td>
                        <?php echo $this->Form->file('input_csv'); ?>
                    </td>
                    <td>
                        <?php echo $this->Form->select('search', array('google' => 'Google', 'bing' => 'Bing', 'youtube' => 'Youtube'), array('default'=>'Google', 'empty'=>false, 'class'=>'form-control')); ?>
                    </td>
                    <td>
                        <div style="padding: 0px 5px 0px 5px;">
                            <?php echo $this->Form->input('proxy', array('type'=>'checkbox', 'label'=>'Using proxy', 'class'=>'chk_proxy')) ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $this->Form->submit('Upload', array('class'=>'btn btn-primary btnSubmit')); ?>
                    </td>
                </tr>

                <tr class="load_proxy">
                    <td>
                        Select proxy list:
                    </td>
                    <td>
                        <?php echo $this->Form->file('input_proxy', array('class'=>'content_proxy')); ?>
                    </td>
                </tr>

            </tbody>
        </table>
    <?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.load_proxy').hide();
        $('.chk_proxy').click(function(){
            $('.load_proxy').toggle();
            if(!$('.load_proxy').is(":visible")){
                $('.content_proxy').replaceWith($('.content_proxy').clone());
            }
        });
    });
</script>