# Copyright(C) 2024 Jason D. McCormick
# Distributed under the "3 Clause BSD License"
# See LICENSE.md with the source

import logging

__BUILD_ID = "@@HEAD-DEVELOP@@"
log = logging.getLogger(__name__)

class WsjtQso:
    """ QSO reported by WSJT-X """

    def __init__(self):       
        self.datetime_off = str()
        self.dx_call = str()
        self.dx_grid = str()
        self.tx_freq = str()
        self.mode = str()
        self.report_sent = str()
        self.report_rcvd = str()
        self.tx_power = str()
        self.comments = str()
        self.name = str()
        self.datetime_on = str()
        self.op_call = str()
        self.de_call = str()
        self.de_grid = str()
        self.exch_sent = str()
        self.exch_rcvd = str()
        self.adif_prop_mode = str()
        self.arrl_class = None
        self.section = None
        self.band = None
        self.qkey = None

class WsjtQsoException(Exception):
    """ exception for WsjtQso """