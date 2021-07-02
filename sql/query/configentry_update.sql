update fi_config_params 
set param_knz= :param_knz, 
    param_desc= :param_desc, 
    param_value= :param_value
where mandant_id = :mandant_id 
    and param_id = :param_id
