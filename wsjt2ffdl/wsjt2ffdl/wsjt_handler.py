# Copyright(C) 2024 Jason D. McCormick
# Distributed under the "3 Clause BSD License"
# See LICENSE.md with the source

import logging
import pprint
import re
import requests
from requests.packages.urllib3.exceptions import InsecureRequestWarning
import socketserver
from . import wsjt_decoder, wsjt_qso

__BUILD_ID = "@@HEAD-DEVELOP@@"
log = logging.getLogger(__name__)

requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

class WsjtHandler(socketserver.BaseRequestHandler):
    """ handler class for the UDP server """
    def handle(self):
        data = self.request[0].strip()
        socket = self.request[1]
        #log.debug(f"Received {len(data)} bytes from {self.client_address[0]}")
        r = wsjt_decoder.decode_message(data, self.client_address[0])
        
        if r != None:
            log.debug(f"call: {r.dx_call}")
            log.debug(f"logclock: {r.datetime_on}")
            r.band = self.__xfreq_to_band(r.tx_freq)
            log.debug(f"band: {r.band}")
            log.debug(f"exch_rcvd: {r.exch_rcvd}")

            try:
                r.arrl_class , r.section = re.split(r"\s+", r.exch_rcvd)
                log.debug(f"opclass: {r.arrl_class}")
                log.debug(f"section: {r.section}")
            except ValueError as e:
                log.error("improper exchange data for FD from WSJT-X: %s", r.exch_rcvd)
                log.error("ValueError was %s", e)
                return False
                        
            r.mode = "DATA"
            log.debug(f"mode: {r.mode}")
            log.debug(f"callsign: {r.de_call}")
            

            if r.op_call is None or r.op_call == "":
                r.op_call = r.de_call
            log.debug(f"operator: {r.op_call}")
            r.qkey = self.__gen_qkey(r.dx_call, r.band, r.mode)
            log.debug(f"qkey: {r.qkey}")
            
            stored = self.__post_qso(r)
            if not stored:
                log.error("Failed to post qkey: %s", r.qkey)
                return False
            
            return True

    def __post_qso(self, r: wsjt_qso.WsjtQso):
        post = {
            "qkey" : r.qkey,
            "logclock" : r.datetime_on,
            "call" : r.dx_call,
            "opclass" : r.arrl_class,
            "section" : r.section,
            "opcallsign" : r.op_call,
            "opoperator" : r.de_call,
            "opband" : r.band,
            "opmode" : r.mode
        }

        try:
            resp = requests.post(self.server.ffdl_url, data=post, verify=False)
            
            if resp.status_code != 200:
                log.error("Failed to POST with HTTP status: %d", resp.status_code)
                log.error("Output (if any): %s", resp.text)
        
            if re.search(r"^OK", resp.text):
                log.info("Logged: %s", r.qkey)
                return True

            if re.search(r"^ERROR", resp.text):
                log.error("Server Error: %s", resp.text)
                return False

        except Exception as e:    
            log.error(e)
            return False 
        
        return False

    def __gen_qkey(self, call: str, band: str, mode: str):
        call = call.upper()
        band = band.upper()
        return f"{call}{band}{mode}"
            
    def __xfreq_to_band(self, freq: int):
        if freq >= 1800000 and freq <= 2000000:
            return "160M"
        if freq >= 3500000 and freq <= 4000000:
            return "80M"
        if freq >= 7000000 and freq <= 7300000:
            return "40M"
        if freq >= 14000000 and freq <= 14350000:
            return "20M"
        if freq >= 21000000 and freq <= 21450000:
            return "15M"
        if freq >= 28000000 and freq <= 29700000:
            return "10M"
        if freq >= 50000000 and freq <= 54000000:
            return "6M"
        if freq >= 144000000 and freq <= 146000000:
            return "2M"
        if freq >= 420000000 and freq <= 450000000:
            return "70CM"
        
        return "UNKN"
        
